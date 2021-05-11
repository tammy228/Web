<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserEmailExistedValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UserEmailExisted */

        if (null === $value || '' === $value) {
            return;
        }

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->em->getRepository(User::class);
        $existedUserEmail = $userRepository->findOneBy(['email' => $value]);


        if($existedUserEmail) {
            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
