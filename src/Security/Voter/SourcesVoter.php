<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Sources;

class SourcesVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['SOURCES_VIEW', 'SOURCES_ADMIN'])
            && $subject instanceof Sources;
    }

    /**
     * @param string $attribute
     * @param Sources $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'SOURCES_VIEW':
                return $subject->getViewUser()->contains($user);
            case 'SOURCES_ADMIN':
                return $subject->getAdminUser()->contains($user);
        }

        return false;
    }
}
