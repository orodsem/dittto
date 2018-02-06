<?php

namespace Dittto\RecognitionBundle\Form;


use Dittto\RecognitionBundle\Entity\Recognition;
use Dittto\RecognitionBundle\Entity\Repository\CriteriaRepository;
use Dittto\UserBundle\Entity\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecognitionType extends AbstractType
{
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = isset($options['user']) ? $options['user'] : null;

        $builder->add('criteria',
            EntityType::class, array(
                'class' => 'DitttoRecognitionBundle:Criteria',
                'query_builder' => function(CriteriaRepository $repo) {
                    return $repo->getVisible();
                },
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
                'query_builder' => function(UserRepository $repo) {
                    return $repo->getEligibleRecognitionUsers($this->user);
                },
                'choice_label' => 'fullname',
                'multiple' => true,
                'expanded' => false,
                'constraints' => array
                (
                    new NotBlank(array('message' => 'Select receivers!'))
                )
            ));

        $builder->add('comment', TextareaType::class, array('required'   => false));

        $builder->add('save', SubmitType::class, array('label' => 'Dittto'));

    }

    public function getName()
    {
        return 'recognition';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => Recognition::class,
            'user'          => null
        ));
    }
}