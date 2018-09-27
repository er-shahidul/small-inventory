<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;

/**
 * Class Product
 *
 * @package AppBundle\Datatables
 */
class ProductDatatable extends BaseDatatable
{
    public $i=1;
    public function getLineFormatter()
    {
        $formatter = function($line){
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

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('products_home')]));

        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
        ]));

        $this->columnBuilder
            ->add('name', Column::class, array(
                'title' => 'Product Name',
                ))
            ->add('type', Column::class, array(
                'title' => 'Type',
                ))
//            ->add(null, ActionColumn::class, array(
//                'start_html' => '<div class="btn-group">
//                                 <button class="btn btn-xs green dropdown-toggle btn-circle" type="button" data-toggle="dropdown" aria-expanded="false">
//                                 Actions<i class="fa fa-angle-down"></i>
//                                 </button>
//                                 <ul class="dropdown-menu pull-right">',
//                'end_html' => '</ul></div>',
//                'title' => $this->translator->trans('sg.datatables.actions.title'),
//                'actions' => array(
//                    array(
//                        'route' => 'bank_update',
//                        'route_parameters' => array(
//                            'id' => 'id'
//                        ),
//                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
//                        'icon' => 'fa fa-edit',
//                        'attributes' => array(
//                            'rel' => 'tooltip',
//                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
//                            'class' => 'btn btn-default btn-xs',
//                            'role' => 'button'
//                        ),
//                        'start_html' => '<li>',
//                        'end_html' => '</li>',
//                    )
//                )
//            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Product';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'product_datatable';
    }
}
