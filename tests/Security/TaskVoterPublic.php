<?php

namespace App\Tests\Security;

use App\Security\TaskVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoterPublic extends TaskVoter
{
    public function supports($attribute, $subject): bool
    {
        return parent::supports($attribute, $subject);
    }

    public function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        return parent::voteOnAttribute($attribute, $subject, $token);
    }
}
