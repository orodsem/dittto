<?php

namespace Dittto\RecognitionBundle\Form;


use Dittto\RecognitionBundle\Entity\Recognition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecognitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('criteria',
            EntityType::class, array(
                'class' => 'DitttoRecognitionBundle:Criteria',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'constraints' => array
                (
                    new NotBlank(array('message' => 'Select a criteria!'))
                )
            ))->add('receivers',
            EntityType::class, array(
                'class' => 'DitttoUserBundle:User',
                'choice_label' => 'fullname',
                'multiple' => true,
                'expanded' => false,
                'constraints' => array
                (
                    new NotBlank(array('message' => 'Select receivers!'))
                )
            ));

        $builder->add('recognitionReceiveds',
            ChoiceType::class
        );

        $builder->add('foo', ChoiceType::class, array(
            'choices'  => array(
                'Maybe' => null,
                'Yes' => true,
                'No' => false,
            ),
        ));

        $builder->add('save', SubmitType::class, array('label' => 'Dittto'));

    }

    public function getName()
    {
        return 'recognition';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Recognition::class,
        ));
    }
}