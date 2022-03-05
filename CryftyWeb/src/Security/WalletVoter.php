<?php

namespace App\Security;

use App\Entity\Crypto\Wallet;
use App\Entity\Users\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WalletVoter extends \Symfony\Component\Security\Core\Authorization\Voter\Voter
{

    const ACCESS = 'access';


    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if ($attribute != self::ACCESS) {
            return false;
        }


        if (!$subject instanceof Wallet) {
            return false;
        }

        return true;
    }


    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Wallet $wallet */
        $wallet = $subject;

        switch ($attribute) {
            case self::ACCESS:
                return $this->hasAccess($wallet, $user);
        }

        throw new \LogicException('outOfBoundCode');
    }

    private function hasAccess(Wallet $wallet, User $user) : bool
    {
        return $user === $wallet->getClient();
    }
}