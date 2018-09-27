<?php

namespace Bundle\UserBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class User Datatable
 *
 * @package Bundle\UserBundle\Datatables
 */
class UserDatatable extends BaseDatatable
{
    public $i=1;
    public function getLineFormatter()
    {
        $formatter = function($line){
            $user = $this->em->getRepository('UserBundle:User')->find($line['id']);
            $profile = $this->em->getRepository('UserBundle:Profile')->findOneBy(['user' => $user->getId()]);
            $line['photo'] = sprintf('<img src="/%s" width="50">', $profile->getPhoto());
            $line["isSuperAdmin"] = !$user->isSuperAdmin();
            $line['enabled'] = $user->isEnabled();
            $line['disabled'] = !$user->isEnabled();
            $line['sl'] = $this->i++;
            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('users_home')]));

        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
        ]));
//        $this->setDefaultExportButtons([2,3,4,5]);

        $this->columnBuilder
            ->add('sl', VirtualColumn::class, array('title' => 'Sl',
            ))
            ->add('photo', VirtualColumn::class, array(
                'title' => 'Photo',
                'width' => '70px',
            ))
            ->add('profile.fullNameEn', Column::class, array(
                'title' => 'Full Name',
            ))
            ->add('username', Column::class, array(
                'title' => 'Username'
            ))
            ->add('profile.designation', Column::class, array(
                'title' => 'Designation',
            ))
            ->add('profile.cellphone', Column::class, array(
                'title' => 'Cellphone',
            ))
            ->add('enabled', VirtualColumn::class, array('visible' => false))
            ->add('disabled', VirtualColumn::class, array('visible' => false))
            ->add(null, ActionColumn::class, array(
                'start_html' => '<div class="btn-group">
                                 <button class="btn btn-xs green dropdown-toggle btn-circle" type="button" data-toggle="dropdown" aria-expanded="false"> 
                                 Actions<i class="fa fa-angle-down"></i></button>
                                 <ul class="dropdown-menu pull-right">',
                'end_html'   => '</ul></div>',
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => 'user_update',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-default btn-xs',
                            'role' => 'button'
                        ),
                        'start_html' => '<li>',
                        'end_html' => '</li>',
                    ),
                    array(
                        'route' => 'user_details',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' =>  $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' =>  $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-default btn-xs',
                            'role' => 'button'
                        ),
                        'start_html' => '<li>',
                        'end_html' => '</li>',
                    ),
                    array(
                        'route' => 'user_delete',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Delete',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Delete',
                            'class' => 'btn btn-default btn-xs',
                            'role' => 'button'
                        ),
                        'confirm' => false,
                        'confirm_message' => 'Are you sure?',
                        'render_if' => function ($row) {
                            return $row['isSuperAdmin'];
                        },
                        'start_html' => '<li>',
                        'end_html' => '</li>',
                    ),
                    array(
                        'route' => 'user_enabled',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Enable',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'enable-action',
                            'class' => 'btn btn-default btn-xs delete-list-btn',
                            'role' => 'button'
                        ),
                        'confirm' => false,
                        'confirm_message' => 'Are you sure?',
                        'render_if' => function ($row) {
                            return $row['disabled'] && $row['isSuperAdmin'];
                        },
                        'start_html' => '<li>',
                        'end_html' => '</li>',
                    ),
                    array(
                        'route' => 'user_enabled',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => 'Disable',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'disable-action',
                            'class' => 'btn btn-default btn-xs delete-list-btn',
                            'role' => 'button'
                        ),
                        'confirm' => false,
                        'confirm_message' => 'Are you sure?',
                        'render_if' => function ($row) {
                            return $row['enabled'] && $row['isSuperAdmin'];
                        },
                        'start_html' => '<li>',
                        'end_html' => '</li>',
                    )
                )
            ))
        ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'Bundle\UserBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}
