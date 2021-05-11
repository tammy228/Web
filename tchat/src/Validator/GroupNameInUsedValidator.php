<?php

namespace App\Validator;

use App\Entity\ChatRoom;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class GroupNameInUsedValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\FriendExisted */

        if (null === $value || '' === $value) {
            return;
        }

        /**
         * @var UserRepository $userRepository
         */
        $chatRoomRepository = $this->em->getRepository(ChatRoom::class);
        $existedGroupName = $chatRoomRepository->findOneBy(['name' => $value]);

        if($existedGroupName) {
            // TODO: implement the validation here
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
