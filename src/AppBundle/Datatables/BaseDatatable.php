<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Style;
use AppBundle\Datatables\Column\ActionDropDownColumn;

/**
 * Class Base Datatable
 *
 * @package AppBundle\Datatables
 */
class BaseDatatable extends AbstractDatatable
{
    protected $actionButtons = [];
    private $dateFields = [];
    public $actionButtonType = 'flat';

        public function getCustomLineFormatter() {
        return null;
    }

    public function getLineFormatter()
    {
        $custom = $this->getCustomLineFormatter();
        $formatter = function($line ) use ($custom){

            if($custom !== null && is_callable($custom)) {
                $line = call_user_func($custom, $line);
            }

            foreach ($this->dateFields as $field) {
                $line[$field.'Virtual'] = $line[$field]->format('Y-m-d');
            }

            return $line;
        };

        return $formatter;
    }

    public function initActionButtons()
    {
        if ($actionButtons = $this->actionButtons) {
            $this->buildFlatActionButtons();
            $this->buildDropDownActionButtons();
        }
    }

    protected function buildFlatActionButtons()
    {
        if ($this->actionButtonType != 'flat') return;

        $this->columnBuilder->add(null, ActionColumn::class, [
            'title' => 'Actions',
            'actions' => $this->actionButtons
        ]);
    }

    protected function buildDropDownActionButtons()
    {
        if ($this->actionButtonType != 'dropdown') return;

        $this->columnBuilder->add(null, ActionColumn::class, [
            'title' => 'Actions',
            'actions' => $this->actionButtons,
            'start_html' => '<div class="btn-group"><button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions<i class="fa fa-angle-down"></i></button><ul class="dropdown-menu" role="menu">',
            'end_html' => '</ul></div>'
        ]);
    }

    public function addActionButton($route, $title, $icon, $routeParam = [], $renderIf = null)
    {
        $this->actionButtons[] = array(
            'route' => $route,
            'route_parameters' => $routeParam,
            'label' => $title,
            'icon' => 'glyphicon ' . $icon,
            'attributes' => array(
                'rel' => 'tooltip',
                'title' => $title,
                'class' => $this->actionButtonType == 'flat' ? 'btn btn-primary btn-xs' : '',
                'role' => 'button'
            ),
            'render_if' => $renderIf,
            'start_html' => $this->actionButtonType == 'dropdown' ? '<li>' : '',
            'end_html' => $this->actionButtonType == 'dropdown' ? '</li>' : ''
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()){}

    /**
     * {@inheritdoc}
     */
    public function getEntity(){}

    /**
     * {@inheritdoc}
     */
    public function getName(){}

    public function getDefaultFeatures($overrideFeatures = array())
    {
        return array_merge(
            array(
                'auto_width' => true,
                'defer_render' => false,
                'info' => true,
                'length_change' => true,
                'ordering' => true,
                'paging' => true,
                'processing' => true,
                'scroll_x' => false,
                'scroll_y' => '',
                'searching' => true,
                'state_save' => false,
            ), $overrideFeatures
        );
    }

    public function getDefaultOptions($overrideOptions = array())
    {
        return array_merge(
            array(
                'paging_type' => Style::FULL_NUMBERS_PAGINATION,
                'scroll_collapse' => false,
                'search_delay' => 500,
                'state_duration' => 7200,
                'stripe_classes' => array(),
                'classes' => Style::BOOTSTRAP_3_STYLE,
                'individual_filtering' => false,
                'individual_filtering_position' => 'head',
                'row_id' => 'id'
            ), $overrideOptions
        );
    }

    public function getDefaultAjax($override = array())
    {
        return array_merge(
            array(
                'url' => '',
                'type' => 'GET',
                'pipeline' => 0
            ), $override
        );
    }

    public function setDefaultExportButtons($columns)
    {
        $this->extensions->set([
            'buttons' => [
                'show_buttons' => [
                    [
                        'extend'        => 'print',
                        'exportOptions' => [
                            'columns' => $columns,
                        ]
                    ],
                    [
                        'extend'        => 'pdf',
                        'exportOptions' => [
                            'columns' => $columns,
                        ]
                    ],
                    [
                        'extend'        => 'excel',
                        'exportOptions' => [
                            'columns' => $columns,
                        ]
                    ]
                ]
            ]
        ]);

        /**
        If callbacks require override, do as follow in datatable class
         $this->callbacks->set(
            [
                'draw_callback' => $this->callbacks->getDrawCallback()
            ]
            );

         */
        $this->callbacks->set(
            [
                'draw_callback' => array(
                    'template' => '@App/_template/_datatable_draw_callbackjs.twig',
                )
            ]
        );
    }

    protected function addDateColumn($dql, $title)
    {
        $this->dateFields[] = $dql;
        $this->columnBuilder
            ->add($dql, Column::class, array(
                'visible' => false,
            ))
            ->add($dql.'Virtual', VirtualColumn::class, array(
                'title' => $title,
                'searchable' => true,
                'orderable' => true,
                'order_column' => $dql,
                'search_column' => $dql,
                'filter' => array(DateRangeFilter::class, array(
                    'cancel_button' => true
                ))
            ));
    }

}
