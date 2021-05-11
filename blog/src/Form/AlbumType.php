<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Photo;
use App\Form\DataTransformer\TextToDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AlbumType extends AbstractType
{
    private $transformer;
    public function __construct(TextToDataTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', TextType::class, array(
                'required' => true
            ))
            ->add('description', TextareaType::class, array(
                'required' => false
            ))

            ->add('offlineAt', TextType::class, array(
                'required' => false,
                'invalid_message' => "this is not a valid offline time."
            ));
        $builder->get('offlineAt')
            ->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Album::class,
        ]);
    }
}
