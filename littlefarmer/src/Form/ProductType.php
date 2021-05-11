<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zhName')
            ->add('enName')
            ->add('image', FileType::class, array(
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ))
            ->add('zhDescription', TextareaType::class)
            ->add('enDescription', TextareaType::class)
            ->add('price', IntegerType::class)
            ->add('stock', IntegerType::class)
            ->add('detail')
            ->add('origin')
            ->add('groupBuy',ChoiceType::class, [
                'choices' => [
                    '否' => 0,
                    '是' => 1,
                ]
            ])
            ->add('onSale', ChoiceType::class, [
                'choices' => [
                    '否' => 0,
                    '是' => 1,
                ]
            ])
            ->add('expired', ChoiceType::class, [
                'choices' => [
                    '否' => 0,
                    '是' => 1,
                ]
            ])




        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
