<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zhName')
            ->add('enName')
//            ->add('thumbnail', FileType::class, array(
//                'mapped' => false,
//                'required' => true,
//                'data_class' => null,
//                'multiple' => false,
//                'attr' => [
//                    'accept' => 'image/*'
//                ]
//            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
