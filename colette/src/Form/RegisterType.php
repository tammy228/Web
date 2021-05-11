<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\UserEmailInUsed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'required' => true,
                'constraints' => [
                    new UserEmailInUsed()
                ]])
            ->add('name', TextType::class,['required' => true,])
            ->add('plainPassword', RepeatedType::class, array(
                'mapped' => false,
                'type' => PasswordType::class,
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => '密碼需要至少 {{ limit }} 個字元',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ))
            ->add('address', TextType::class,[
                'mapped' => false,
                'required' => true,
            ])
            ->add('mobile', TextType::class,['required' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}