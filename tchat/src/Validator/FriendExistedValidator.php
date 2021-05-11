<?php

namespace App\Validator;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Entity\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FriendExistedValidator extends ConstraintValidator
{
    private $em;
    private $security;
    private $userService;

    public function __construct(EntityManagerInterface $entityManager,
                                UserService $userService,
                                Security $security)
    {
        $this->em = $entityManager;
        $this->security = $security;
        $this->userService = $userService;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\FriendExisted */

        if (null === $value || '' === $value) {
            return;
        }

        $friends = $this->userService->listFriends($this->security->getUser());
        $existedFriend = false;
        foreach ($friends as $friend)
        {
            if($friend->getName() == $value)
                $existedFriend = true;
        }

        if($existedFriend) {
            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
