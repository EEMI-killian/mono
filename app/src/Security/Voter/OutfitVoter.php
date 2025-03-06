<?php 

namespace App\Security\Voter;

use App\Entity\Outfit;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class OutfitVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Outfit;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var Outfit $outfit */
        $outfit = $subject;

        return match($attribute) {
            self::VIEW => $this->canView($outfit, $user),
            self::EDIT => $this->canEdit($outfit, $user),
            self::DELETE => $this->canDelete($outfit, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(Outfit $outfit, User $user): bool
    {
        return $outfit->isPublic() || $outfit->getUserId() === $user;
    }

    private function canEdit(Outfit $outfit, User $user): bool
    {
        return $outfit->getUserId() === $user;
    }

    private function canDelete(Outfit $outfit, User $user): bool
    {
        return $outfit->getUserId() === $user;
    }
}