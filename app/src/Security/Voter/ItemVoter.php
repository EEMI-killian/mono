<?php
namespace App\Security\Voter;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ItemVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Item;
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

        /** @var Item $item */
        $item = $subject;

        return match($attribute) {
            self::EDIT => $this->canEdit($item, $user),
            self::DELETE => $this->canDelete($item, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit(Item $item, User $user): bool
    {
        return $item->getUserId() === $user;
    }

    private function canDelete(Item $item, User $user): bool
    {
        return $item->getUserId() === $user;
    }
}