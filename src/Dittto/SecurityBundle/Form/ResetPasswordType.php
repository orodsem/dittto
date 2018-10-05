<?php

namespace Dittto\SecurityBundle\Form;


use Dittto\SecurityBundle\Entity\ResetPassword;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('identifier', EmailType::class, array(
            'required'   => true,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Username or Email address'
            )
        ));

        $builder->add('save', SubmitType::class, array(
            'label' => 'Send recovery link',
            'attr' => array('class' => 'btn btn-lg btn-primary btn-block forgot-password-submit-btn')
        ));
    }

    public function getName()
    {
        return 'recognition';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => ResetPassword::class,
            'user'          => null
        ));
    }
}