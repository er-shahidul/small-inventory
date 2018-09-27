<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class WorkOrderForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workOrderNo', NULL, [
                'label' => 'Work Order No',
                'attr' => array(
                    'placeholder' => 'Work Order No',
                ),
            ])
            ->add('institution', EntityType::class, [
                'class' => 'AppBundle\Entity\Institution',
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Select Bank',
                'attr' => array(
                    'class' => 'form-control select2me',
                ),
            ])
            ->add('product', EntityType::class, [
                'class' => 'AppBundle\Entity\Product',
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Select Product',
                'attr' => array(
                    'class' => 'form-control select2me',
                ),
            ])
            ->add('quantity', NULL, [
                'label' => 'Quantity',
                'attr' => array(
                    'placeholder' => 'Quantity',
                ),
            ])
            ->add('batchNo', NULL, [
                'label' => 'Batch No',
                'attr' => array(
                    'placeholder' => 'Batch No',
                ),
            ])
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
            'data_class' => 'AppBundle\Entity\WorkOrder'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_work_order';
    }
}
