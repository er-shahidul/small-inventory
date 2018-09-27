<?php

namespace Bundle\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserForm extends AbstractType
{
    /** @var User */
    protected $loginUser = null;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    public function __construct($user, AuthorizationCheckerInterface $authorizationCheckerInterface)
    {
        $this->authorizationChecker = $authorizationCheckerInterface;
    }

    public function setLoginUser(User $user)
    {
        $this->loginUser = $user;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $loginUser = $this->loginUser;
        $builder->add('profile', ProfileForm::class);
        /** @var User $data */
        $data = $options['data'];
        $attr = $options['attr'];

        if ($data->getId() === null) {
        }
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

        $passwordConstrain = $data->getId() ? array() : array(
            new NotBlank(
                array(
                    'message' => 'Password should not be blank',
                )
            ),
            new Length(array('min' => 6)),
        );

        if ($data->getId() === null || $data->getId() == $this->loginUser->getId() || $this->authorizationChecker->isGranted('ROLE_CHANGE_USER_PASSWORD')) {
        }
            $builder->add(
                'plainPassword',
                RepeatedType::class,
                array(
                    'type'            => PasswordType::class,
                    'options'         => array('translation_domain' => 'FOSUserBundle'),
                    'first_options'   => array('label' => 'form.password'),
                    'second_options'  => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                    'constraints'     => $passwordConstrain,
                    'required'        => false
                )
            );


        $builder->add('groups', EntityType::class, array(
            'class' => 'Bundle\UserBundle\Entity\UserGroup',
            'query_builder' => function(EntityRepository $groupRepository) use ($loginUser){
                $qb = $groupRepository->createQueryBuilder('g')
                    ->andWhere("g.name != :group")->setParameter('group', 'Super Administrator');

                return $qb;
            },
            'choice_label' => 'name',
            'multiple' => true,
            'label' => 'Role',
            'required' => false
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

    public function getName()
    {
        return 'user';
    }
}