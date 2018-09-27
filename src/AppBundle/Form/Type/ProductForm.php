<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', NULL, [
                'label' => 'Product Name',
                'attr' => array(
                    'placeholder' => 'Product Name',
                ),
            ])
            ->add('category', ChoiceType::class, array(
                'label' => 'Category',
                'choices' => array(
                    'VISA' => Property::VISA,
                    'MASTER' => Property::MASTER
                ),
                'multiple' => false,
                'required' => false
            ))
            ->add('note', TextareaType::class, [
                'label' => 'Note',
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
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_product';
    }
}
