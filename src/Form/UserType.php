<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'entity.user.email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'entity.user.password'
            ])
            ->add('nickName', TextType::class, [
                'label' => 'entity.user.nickName'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'entity.user.firstName'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'entity.user.lastName'
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'entity.user.birthDate'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'common.link.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
