<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsType extends AbstractType
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
            ->add('enTitle', TextType::class, array(
                'required' => false
            ))
            ->add('zhTitle', TextType::class, array(
                'required' => true
            ))
            ->add('enContent', TextareaType::class, array(
                'required' => false
            ))
            ->add('zhContent', TextareaType::class, array(
                'required' => true
            ))
            ->add('images', FileType::class, array(
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*'
                ]
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
