<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserEmailVerifyToken;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class FarmerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,array(
            ))
            ->add('hash_key', TextType::class, array(
                'required' => false
            ))
            ->add('hash_iv', TextType::class, array(
                'required' => false
            ))
            ->add('merchant_id', TextType::class, array(
                'required' => false
            ))
            ->add('mobile', TextType::class)
            ->add('image', FileType::class, array(
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ))
            ->add('birthday', DateType::class,array(
                'widget' => 'choice',
                'years' => range(date('Y')-10, date('Y')-90),
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'js-datepicker'],
            ))
            ->add('instruction', TextareaType::class)
            ->add('farm',TextType::class)
            ->add('sexual',ChoiceType::class,array(
                'choices' => array(
                    '未選擇' => null,
                    '男' => 'male',
                    '女' => 'female'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}