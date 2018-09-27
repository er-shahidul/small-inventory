<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\AuditLogDatatable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AuditLogController extends BaseController
{
    /**
     * @Route("/audit-log", name="audit_log_list", options={"expose"=true})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(AuditLogDatatable::class, $request->isXmlHttpRequest(), function($qb)
        {
            /** @var \Doctrine\ORM\QueryBuilder $qb */
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('AppBundle:AuditLog:index.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Audit Log',
        ));
    }
}
