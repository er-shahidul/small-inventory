<?php

namespace Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProfileForm extends AbstractType
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
            ->add('gender', ChoiceType::class, [
                'choices' => array('Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'),
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'data' => 'Male',
                'placeholder' => false,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
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
            'format' => 'y-M-d',
            'attr' => array(
                'class' => 'date-picker'
            ),
            'label' => 'Date of Birth'
        ));
        /*$builder->add('bloodGroup', ChoiceType::class, array(
            'choices' => $this->bloodGroupList(),
            'required'    => false,
            'placeholder' => 'Select',
            'empty_data'  => null,
        ));*/
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

    private function religionList()
    {
        return array(
            'ইসলাম' => 'ইসলাম',
            'হিন্দু' => 'হিন্দু',
            'বৌদ্ধ' => 'বৌদ্ধ',
            'খ্রীস্টান' => 'খ্রীস্টান',
            'প্রকাশ করতে অস্বীকার' => 'প্রকাশ করতে অস্বীকার',
            'নাস্তিক' => 'নাস্তিক',
            'অন্যান্য' => 'অন্যান্য',
        );
    }

    private function bloodGroupList()
    {
        return array(
            'O+ (O Positive)' => 'O+ (O Positive)',
            'O- (O Negative)' => 'O-  (O Negative)',
            'A+ (A Positive)' => 'A+ (A Positive)',
            'A-	(A Negative)' => 'A- (A Negative)',
            'B+ (B Positive)' => 'B+ (B Positive)',
            'B- (B Negative)' => 'B- (B Negative)',
            'AB+ (AB Positive)' => 'AB+ (AB Positive)',
            'AB- (AB  Negative)' => 'AB- (AB Negative)',
        );
    }
}
