<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\DataTransformer\TextToDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;



class ArticleType extends AbstractType
{
    private $transformer;
    public function __construct(TextToDataTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
            ))
            ->add("content",  CKEditorType::class, array(
                "required" => false,
                'config' => array(
                    'language' => 'zh-tw',
                    ),
            ))
            ->add('draft', ChoiceType::class, array(
                "required" => true,
                "choices" => [
                    '草稿' => true,
                    '上傳' => false
                    ],
            ))
            ->add('offlineAt', TextType::class, array(
                "required" => false,
                'invalid_message' => "this is not a valid offline time."
            ))
            ->add('categories', CollectionType::class, array(
                'label' => ' ',
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
            'data_class' => Article::class,
        ]);
    }
}

