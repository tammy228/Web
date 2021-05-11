<?php

namespace App\Validator;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CategoryNameInUsedValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint CategoryNameInUsed */

        if (null === $value || '' === $value) {
            return;
        }

        /**
         * @var CategoryRepository $categoryRepository
         */
        $categoryRepository = $this->em->getRepository(Category::class);
        $existedCategoryName = $categoryRepository->findOneBy(['name' => $value]);

        if($existedCategoryName) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
