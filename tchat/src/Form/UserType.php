<?php

namespace App\Form;

use App\Validator\UsernameInUsed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                "label" => "User Name",
                "required" => true,
                "constraints" =>[
                    new UsernameInUsed()
                ]
            ))
            ->add('email', EmailType::class, array(
                "label" => "User Email",
                "required" => true,
            ))
            ->add("password", PasswordType::class, array(
                "required" => false,
                "empty_data" => ""
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
