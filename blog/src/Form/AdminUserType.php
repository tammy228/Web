<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\AppUserGroupRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                "label" => "User Name",
                "required" => true,
            ))
            ->add('email', EmailType::class, array(
                "label" => "User Email",
                "required" => true,
            ))
            ->add("role", ChoiceType::class, array(
                "mapped" => false,
                "required" => true,
                "choices" => [
                    'Normal User' => "ROLE_USER",
                    'Admin' => "ROLE_ADMIN"
                ]
            ))
            
            
        ;
    }
    
}