<?php

namespace Bundle\UserBundle\Form\Type;

use AppBundle\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class GroupForm extends AbstractType
{
    private $class;
    private $permissionBuilder;

    public function __construct($class, $permissionBuilder)
    {
        $this->class = $class;
        $this->permissionBuilder = $permissionBuilder;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, array(
                'label' => 'Name',
                'choices' => Property::GROUP_TYPE,
                'required' => true,
                'placeholder' => 'Group Name',
                'constraints' => array(
                    new NotBlank(array('message'=>'Name should not be blank'))
                ),
            ));

        $builder->add('description', TextareaType::class, array(
            'label'    => 'Description',
            'required' => false,
            'attr'     => array('class' => 'span5', 'rows' => 3)
        ));

        $builder->add('roles', ChoiceType::class, array(
            'choices'  => $this->permissionBuilder->getPermissionHierarchyForChoiceField(),
            'multiple' => true,
            'constraints' => array(
                new NotBlank(array('message'=>'Roles should not be blank'))
            ),
            'required' => false
        ));

        $builder
            ->add('submit', SubmitType::class, array(
                'attr'     => array('class' => 'btn green'),
                'label' => 'Save'
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'group',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fos_user_group';
    }
}
