<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;

/**
 * Class AuditLogDatatable
 *
 * @package AppBundle\Datatables
 */
class AuditLogDatatable extends BaseDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($line){

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

        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('audit_log_list')]));

        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
        ]));

        $this->columnBuilder
            ->add('id', Column::class, array(
                'visible' => FALSE,
            ))
            ->add('type', Column::class, array(
                'title' => 'Type',
            ))
            ->add('description', Column::class, array(
                'title' => 'Description'
            ))
            ->add('eventTime', DateTimeColumn::class, array(
                'visible' => false,
            ))
            ->add('eventTime', VirtualColumn::class, array(
                'title' => 'Event Time',
                'searchable' => true,
                'orderable' => true,
                'order_column' => 'eventTime',
                'search_column' => 'eventTime',
                'filter' => array(DateRangeFilter::class, array())
            ))
            ->add('user', Column::class, array(
                'title' => 'User'
            ))
            ->add('profileInfo', Column::class, array(
                'title' => 'Personnel',
                'default_content' => 'N/A'
            ))
            ->add('ip', Column::class, array(
                'title' => 'Ip'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\AuditLog';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'auditlog_datatable';
    }
}
