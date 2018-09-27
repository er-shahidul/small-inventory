<?php

namespace AppBundle\Controller;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @param $datatableClass
     * @param $isAjaxRequest
     * @param null $callback
     * @return AbstractDatatable|Response
     * @internal param $datatable
     */
    protected function prepareDatatable($datatableClass, $isAjaxRequest, $callback = null)
    {
        /** @var AbstractDatatable $datatable */
        $datatable =  $this->get('sg_datatables.factory')->create($datatableClass);
        $datatable->buildDatatable();

        if ($isAjaxRequest) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            if ($callback && is_callable($callback)) {
                $qb = $datatableQueryBuilder->getQb();
                $callback($qb);
            }

            return $responseService->getResponse(false);
        }

        return $datatable;
    }

    protected function dispatch($eventName, Event $event)
    {
        $this->get('event_dispatcher')->dispatch($eventName, $event);
    }

    protected function getRepository($entity)
    {
        return $this->getDoctrine()->getRepository($entity);
    }
}
