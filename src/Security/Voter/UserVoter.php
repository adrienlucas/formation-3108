<?php

namespace App\Security\Voter;

use App\Entity\User as DatabaseUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof DatabaseUser;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
                break;
            case self::VIEW:
                return $this->canView($subject, $user);
                break;
        }

        return false;
    }

    private function canEdit(DatabaseUser $subject, UserInterface $user):bool
    {
        if ($subject->isAdmin()) {
            return false;
        }
        if ($user->getUsername() !== $subject->getOwner()) {
            return false;
        }
        return true;
    }

    private function canView(DatabaseUser $subject, UserInterface $user): bool
    {
        return true;
    }

}
