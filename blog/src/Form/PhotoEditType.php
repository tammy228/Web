<?php

namespace App\Form;

use App\Entity\Photo;
use App\Form\DataTransformer\TextToDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoEditType extends AbstractType
{
    private $transformer;
    public function __construct(TextToDataTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', FileType::class, array(
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ))
            ->add('offlineAt', TextType::class, array(
                'required' => false,
                'invalid_message' => "this is not a valid offline time."
            ))
            ->add('albums',  CollectionType::class, array(
                'mapped' => false,
                "allow_add" => true,
                "entry_type" => IntegerType::class
            ));
        $builder->get('offlineAt')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
