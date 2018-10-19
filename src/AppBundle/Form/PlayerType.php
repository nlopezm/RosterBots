<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlayerType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstName', null, array('constraints' => new NotBlank()))
                ->add('lastName', null, array('constraints' => new NotBlank()))
                ->add('speed')
                ->add('strength')
                ->add('agility')
                ->add('salary')
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Player',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }

    public function getName() {
        return 'Player';
    }

}
