<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LeagueType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, array('constraints' => new NotBlank()))
                ->add('starterPlayers')
                ->add('substitutePlayers')
                ->add('salaryCap')
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\League',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }

    public function getName() {
        return 'League';
    }

}
