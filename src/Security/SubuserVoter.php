<?php

namespace App\Security;

use App\Entity\Campain;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SubuserVoter extends Voter
{
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['CAMP_CREATE', 'CAMP_RETRIVE', 'CAMP_EDIT', 'CAMP_DELETE']);
        $supportsSubject = $subject instanceof Campain;

        return $supportsAttribute && $supportsSubject;
    }

       /**
     * @param string $attribute
     * @param Campain $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** ... check if the user is anonymous ... **/
         $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case 'CAMP_CREATE':
                    if ( $this->security->isGranted('ROLE_ADMIN') || $this->security->isGranted('ROLE_USER_MEMBER') ) 
                { return true; } 
                break;
            case 'CAMP_EDIT':
            case 'CAMP_DELETE':
                if ( $this->security->isGranted('ROLE_ADMIN') )
                     { return true; } 
                break;
            case 'CAMP_RETRIVE':
                    return true;
                break;
        }

        return false;
    }
}
