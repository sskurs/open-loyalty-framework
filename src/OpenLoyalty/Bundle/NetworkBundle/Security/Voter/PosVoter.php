<?php
/*
 * Copyright © 2022 Womboto. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\NetworkBundle\Security\Voter;

use OpenLoyalty\Bundle\UserBundle\Entity\User;
use OpenLoyalty\Bundle\UserBundle\Security\PermissionAccess;
use OpenLoyalty\Component\Network\Domain\Network;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class NetworkVoter.
 */
class NetworkVoter extends Voter
{
    const PERMISSION_RESOURCE = 'NETWORK';

    const LIST_NETWORK = 'LIST_NETWORK';
    const EDIT = 'EDIT';
    const CREATE_NETWORK = 'CREATE_NETWORK';
    const VIEW = 'VIEW';

    public function supports($attribute, $subject)
    {
        return $subject instanceof Network && in_array($attribute, [
            self::EDIT, self::VIEW,
        ]) || $subject == null && in_array($attribute, [
            self::LIST_NETWORK, self::CREATE_NETWORK,
        ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $viewAdmin = $user->hasRole('ROLE_ADMIN')
                     && $user->hasPermission(self::PERMISSION_RESOURCE, [PermissionAccess::VIEW]);

        $fullAdmin = $user->hasRole('ROLE_ADMIN')
                     && $user->hasPermission(self::PERMISSION_RESOURCE, [PermissionAccess::VIEW, PermissionAccess::MODIFY]);

        switch ($attribute) {
            case self::LIST_NETWORK:
                return $viewAdmin || $user->hasRole('ROLE_SELLER');
            case self::EDIT:
                return $fullAdmin;
            case self::CREATE_NETWORK:
                return $fullAdmin;
            case self::VIEW:
                return $viewAdmin || $user->hasRole('ROLE_SELLER');
            default:
                return false;
        }
    }
}
