<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CouponType extends AbstractType
{
    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                "required" => true,
                "label" => '折扣方式',
                'choices' => [
                    '折扣(百分比)' => 0,
                    '折讓(價格)' => 1,
                    '指定價錢' => 2,
                ]
            ))
            ->add("target", ChoiceType::class, array(
                "required" => true,
                "label" => '折扣對象',
                'choices' => [
                    '對訂單' => 1,
                    '對每件商品' => 0,
                ]
            ))
            ->add("code", TextType::class,array(
                "required" => true,
                "label" => '優惠碼',
            ))
            ->add("number", IntegerType::class,array(
                "required" => true,
            ))
            ->add("expireAt", DateType::class, [
                // renders it as a single text box
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control input-inline',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
