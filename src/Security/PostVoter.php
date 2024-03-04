<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const POST = 'post';
    const DELETE = 'delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::POST, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();


        if (!$user instanceof User) {
            return false;
        }
        // you know $subject is a Post object, thanks to `supports()`
        /** @var Post $post */
        $post = $subject;

        switch ($attribute) {
            case self::POST:
                return $this->canPost($post, $user);
            case self::EDIT:
                return $this->canEdit($post, $user);
            case self::DELETE:
                return $this->canDelete($post, $user);
            case self::VIEW:
                return $this->canView();
            default:
                return false;
        }
    }

    private function canPost($post, $user): bool
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles) || in_array('ROLE_USER_MEMBER', $roles)) {
            return true;
        }
        return false;
    }

    private function canEdit($post, $user): bool
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {

            return true;
        }
        return false;
    }

    private function canDelete($post, $user): bool
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {

            return true;
        }
        return false;
    }

    private function canView(): bool
    {
        return true;
    }
}
