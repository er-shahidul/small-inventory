<?php

namespace Bundle\UserBundle\Form\Type;

use Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserProfileUpdateForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profile', ProfileUpdateForm::class);

        $builder->add(
            'email',
            EmailType::class,
            array(
                'required'           => false,
                'label'              => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => array(
                    new NotBlank(
                        array(
                            'message' => 'Email should not be blank',
                        )
                    ),
                    new email(),
                ),
            )
        );

        $builder->add('submit', SubmitType::class, array(
            'attr'     => array('class' => 'btn green'),
            'label' => 'Save'
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bundle\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}