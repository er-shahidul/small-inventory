<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bundle\UserBundle\Form\Type;

use Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profile', ProfileForm::class);

        $builder
            ->add(
                'username',
                null,
                array(
                    'required'              => false,
                    'label'              => 'form.username',
                    'translation_domain' => 'FOSUserBundle',
                    'constraints'        => array(
                        new NotBlank(
                            array(
                                'message' => 'Username should not be blank',
                            )
                        ),
                    ),
                )
            );

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

        $builder->add('plainPassword', RepeatedType::class, array(
        'type' => PasswordType::class,
        'options' => array('translation_domain' => 'FOSUserBundle'),
        'first_options' => array('label' => 'form.password'),
        'second_options' => array('label' => 'form.password_confirmation'),
        'invalid_message' => 'fos_user.password.mismatch',
        'constraints' => array(
                new NotBlank(array(
                    'message'=>'Password should not be blank'
                )),
                new Length(array('min' => 6)),
            ),
        ));

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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_user_registration';
    }
}
