<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Controller\Api;

use Broadway\CommandHandling\CommandBus;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use OpenLoyalty\Bundle\NetworkBundle\Form\Type\CreateNetworkFormType;
use OpenLoyalty\Bundle\NetworkBundle\Form\Type\EditNetworkFormType;
use OpenLoyalty\Bundle\NetworkBundle\Model\Network;
use OpenLoyalty\Component\Network\Domain\Command\CreateNetwork;
use OpenLoyalty\Component\Network\Domain\Command\UpdateNetwork;
use OpenLoyalty\Component\Network\Domain\Network as DomainNetwork;
use OpenLoyalty\Component\Network\Domain\NetworkId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NetworkController.
 */
class NetworkController extends FOSRestController
{
    /**
     * Method allows to create new Network.
     *
     * @Route(name="oloy.network.create", path="/network")
     * @Security("is_granted('CREATE_NETWORK')")
     * @Method("POST")
     * @ApiDoc(
     *     name="Create new Network",
     *     section="NETWORK",
     *     input={"class" = "OpenLoyalty\Bundle\NetworkBundle\Form\Type\CreateNetworkFormType", "name" = "network"},
     *     statusCodes={
     *       200="Returned when successful",
     *       400="Returned when form contains errors",
     *     }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function createAction(Request $request): View
    {
        $form = $this->get('form.factory')->createNamed('network', CreateNetworkFormType::class);
        $uuidGenerator = $this->get('broadway.uuid.generator');

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('broadway.command_handling.command_bus');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Network $data */
            $data = $form->getData();
            $id = new NetworkId($uuidGenerator->generate());

            $commandBus->dispatch(
                new CreateNetwork($id, $data->toArray())
            );

            return $this->view(['networkId' => $id->__toString()]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method allows to update Network data.
     *
     * @Route(name="oloy.network.update", path="/network/{network}")
     * @Method("PUT")
     * @Security("is_granted('EDIT', network)")
     * @ApiDoc(
     *     name="Edit Network",
     *     section="NETWORK",
     *     input={"class" = "OpenLoyalty\Bundle\NetworkBundle\Form\Type\EditNetworkFormType", "name" = "network"},
     *     statusCodes={
     *       200="Returned when successful",
     *       400="Returned when form contains errors",
     *       404="Returned when Network does not exits"
     *     }
     * )
     *
     * @param Request   $request
     * @param DomainNetwork $network
     *
     * @return View
     */
    public function updateAction(Request $request, DomainNetwork $network): View
    {
        $form = $this->get('form.factory')->createNamed('network', EditNetworkFormType::class, null, [
            'method' => 'PUT',
        ]);

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('broadway.command_handling.command_bus');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Network $data */
            $data = $form->getData();

            $commandBus->dispatch(
                new UpdateNetwork($network->getNetworkId(), $data->toArray())
            );

            return $this->view(['networkId' => (string) $network->getNetworkId()]);
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Method will return Network details.
     *
     * @Route(name="oloy.network.get", path="/network/{network}")
     * @Route(name="oloy.network.seller.get", path="/seller/network/{network}")
     * @Method("GET")
     * @Security("is_granted('VIEW', network)")
     * @ApiDoc(
     *     name="get Network",
     *     section="NETWORK"
     * )
     *
     * @param DomainNetwork $network
     *
     * @return View
     */
    public function getAction(DomainNetwork $network): View
    {
        return $this->view($network);
    }

    /**
     * Method will return Network details. <br/>
     * You need to provide Network identifier.
     *
     * @Route(name="oloy.network.get_by_identifier", path="/network/identifier/{network}")
     * @Method("GET")
     * @Security("is_granted('VIEW', network)")
     * @ApiDoc(
     *     name="get Network by identifier",
     *     section="NETWORK",
     *     requirements={{"name": "network", "required"=true, "description"="Network identifier", "dataType"="string"}}
     * )
     * @ParamConverter(class="OpenLoyalty\Component\Pos\Domain\Pos", name="network", options={"identifier":true})
     *
     * @param DomainPos $network
     *
     * @return View
     */
    public function getByIdentifierAction(DomainPos $network): View
    {
        return $this->view($network);
    }

    /**
     * Method will return complete list of Network.
     *
     * @Route(name="oloy.network.list", path="/network")
     * @Route(name="oloy.network.seller.list", path="/seller/network")
     * @Method("GET")
     * @Security("is_granted('LIST_NETWORK')")
     * @ApiDoc(
     *     name="get Network list",
     *     section="NETWORK",
     *     parameters={
     *      {"name"="page", "dataType"="integer", "required"=false, "description"="Page number"},
     *      {"name"="perPage", "dataType"="integer", "required"=false, "description"="Number of elements per page"},
     *      {"name"="sort", "dataType"="string", "required"=false, "description"="Field to sort by"},
     *      {"name"="direction", "dataType"="asc|desc", "required"=false, "description"="Sorting direction"},
     *     }
     * )
     *
     * @param Request $request
     *
     * @return View
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getListAction(Request $request): View
    {
        $pagination = $this->get('oloy.pagination')->handleFromRequest($request);

        $networkRepository = $this->get('oloy.network.repository');
        $network = $networkRepository
            ->findAllPaginated(
                $pagination->getPage(),
                $pagination->getPerPage(),
                $pagination->getSort(),
                $pagination->getSortDirection()
            );
        $total = $networkRepository->countTotal();

        return $this->view(
            [
                'network' => $network,
                'total' => $total,
            ],
            200
        );
    }

    /**
     * Method allows to join seller network.
     *
     * @param SellerDetails $seller
     *
     * @return \FOS\RestBundle\View\View
     * @Route(name="oloy.network.join", path="/network/join")
     * @Method("POST")
     * @Security("is_granted('JOIN_NETWORK', seller)")
     * @ApiDoc(
     *     name="Join Network",
     *     section="Seller"
     * )
     */
    public function joinNetworkAction(Request $request, SellerDetails $seller)
    {
        $form = $this->get('form.factory')->createNamed('network', SellerEditFormType::class, [], [
            'method' => 'PUT',
        ]);

        if (!$this->isGranted('ASSIGN_SELLER_TO_NETWORK')) {
            $sellerRequest = $request->request->get('seller');
            unset($sellerRequest['networkId']);
            $request->request->set('seller', $sellerRequest);
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->get('oloy.user.form_handler.seller_edit')->onSuccess($seller->getSellerId(), $form) === true) {
                if ($form->getData()['active']) {
                    $this->get('broadway.command_handling.command_bus')
                        ->dispatch(
                            new JoinNetwork($seller->getSellerId())
                        );
                } else {
                    $this->get('broadway.command_handling.command_bus')
                        ->dispatch(
                            new LeaveNetwork($seller->getSellerId())
                        );
                }

                return $this->view([
                    'sellerId' => (string) $seller->getSellerId(),
                ]);
            } else {
                return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }    
}
