<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zhName',TextType::class, array(
                'required' => true
            ))
            ->add('enName', TextType::class, array(
                'required' => true
            ))
            ->add('image', FileType::class, array(
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ))
            ->add('zhDescription', TextareaType::class, array(
                'required' => true
            ))
            ->add('enDescription', TextareaType::class, array(
                'required' => true
            ))
            ->add('temperature',TextType::class, array(
                'required' => true
            ))
            ->add('category',  CollectionType::class, array(
                'mapped' => false,
                "allow_add" => true,
                "entry_type" => IntegerType::class
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
