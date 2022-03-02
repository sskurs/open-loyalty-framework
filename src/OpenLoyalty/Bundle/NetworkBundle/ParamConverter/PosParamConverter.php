<?php
/**
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\ParamConverter;

use OpenLoyalty\Component\Network\Domain\Network;
use OpenLoyalty\Component\Network\Domain\NetworkId;
use OpenLoyalty\Component\Network\Domain\NetworkRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class NetworkParamConverter.
 */
class NetworkParamConverter implements ParamConverterInterface
{
    /**
     * @var NetworkRepository
     */
    protected $repository;

    /**
     * NetworkParamConverter constructor.
     *
     * @param NetworkRepository $repository
     */
    public function __construct(NetworkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request        $request       The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $options = $configuration->getOptions();

        if (isset($options['identifier']) && $options['identifier']) {
            $identifier = true;
        } else {
            $identifier = false;
        }

        if (null === $request->attributes->get($name, false)) {
            $configuration->setIsOptional(true);
        }
        $value = $request->attributes->get($name);
        if ($identifier) {
            $object = $this->repository->oneByIdentifier($value);
        } else {
            $object = $this->repository->byId(new NetworkId($value));
        }

        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $configuration->getClass()));
        }
        $request->attributes->set($name, $object);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Network::class;
    }
}
