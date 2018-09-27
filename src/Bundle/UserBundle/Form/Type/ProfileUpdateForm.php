<?php

namespace Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileUpdateForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'fullNameEn',
                null,
                [
                    'required'   => false,
                    'label'      => 'Full Name (English)',
                    'constraints' => [
                        new NotBlank()
                    ]
                ]
            )
            ->add('designation')
            ->add('cellphone', null, [
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('photoFile', FileType::class, array(
                'required' => false,
                'attr' => array(
                    'accept' => "image/*"
                )
            ))
        ;

        $builder->add('currentAddress');
        $builder->add('permanentAddress');
        $builder->add('dob', null, array(
            'widget' => 'single_text',
            'format' => 'd-M-y',
            'attr' => array(
                'class' => 'date-picker'
            ),
            'label' => 'Date of Birth'
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bundle\UserBundle\Entity\Profile'
        ));
    }

    public function getName()
    {
        return 'user_profile';
    }
}
