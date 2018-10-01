<?php

namespace AppBundle\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InstitutionForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institution', EntityType::class, [
                'class' => 'AppBundle\Entity\Institution',
                'required' => false,
                'label' => 'Bank',
                'choice_label' => 'name',
                'placeholder' => 'Select Bank',
                'mapped' => false,
                'attr' => array(
                    'class' => 'form-control select2me',
                ),
            ])
            ->add('submit', SubmitType::class, array(
                'attr'     => array('class' => 'btn green')
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'institution';
    }
}
