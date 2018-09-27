<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\WorkOrderDatatable;
use AppBundle\Entity\History;
use AppBundle\Entity\Inventory;
use AppBundle\Entity\WorkOrder;
use AppBundle\Form\Type\WorkOrderForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class WorkOrderController extends BaseController
{
    /**
     * @Route("/work/orders", name="work_orders_home", options={"expose"=true})
     * @Template()
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(WorkOrderDatatable::class, $request->isXmlHttpRequest(), function($qb)
        {
            /** @var \Doctrine\ORM\QueryBuilder $qb */
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('AppBundle:WorkOrder:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/work/order/create", name="work_order_create", options={"expose"=true})
     * @Template("@App/WorkOrder/form.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $workOrder = new WorkOrder();
        $form = $this->createForm(WorkOrderForm::class, $workOrder);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $workOrder->setStatus('OPEN');
            $this->getDoctrine()->getRepository('AppBundle:WorkOrder')->create($workOrder);

            $this->get('session')->getFlashBag()->add(
                'success',
                'Work Order Create Successfully'
            );

            return $this->redirect($this->generateUrl('work_orders_home'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/work/order/accept/{id}", name="work_order_accept", options={"expose"=true})
     * @param WorkOrder $workOrder
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function acceptAction(Request $request, WorkOrder $workOrder)
    {
        $qty = $workOrder->getQuantity();
        $pdt = $workOrder->getProduct();
        $ins = $workOrder->getInstitution();

        $workOrder->setStatus("ACCEPTED");
        $this->get('session')->getFlashBag()->add(
            'success',
            'Work Order Accepted Successfully'
        );

        $this->getDoctrine()->getRepository('AppBundle:WorkOrder')->update($workOrder);

        if($workOrder->getStatus()=="ACCEPTED"){
            $this->acceptInventoryPlastic($ins, $pdt, $qty);
            $this->acceptInventoryPin($ins, $pdt, $qty);
        }

        return $this->redirect($this->generateUrl('work_orders_home'));
    }

    /**
     * @Route("/work/order/reject/{id}", name="work_order_reject", options={"expose"=true})
     * @param WorkOrder $workOrder
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function rejectAction(Request $request, WorkOrder $workOrder)
    {
        $qty = $workOrder->getQuantity();
        $pdt = $workOrder->getProduct();
        $ins = $workOrder->getInstitution();
        $previousStatus = $workOrder->getStatus();

        $workOrder->setStatus("REJECT");
        $this->get('session')->getFlashBag()->add(
            'success',
            'Work Order Rejected Successfully'
        );

        $this->getDoctrine()->getRepository('AppBundle:WorkOrder')->update($workOrder);

        if($workOrder->getStatus()=="REJECT"){
            $this->rejectInventoryPlastic($ins, $pdt, $qty, $workOrder, $previousStatus);
            $this->rejectInventoryPin($ins, $pdt, $qty, $workOrder, $previousStatus);
        }

        return $this->redirect($this->generateUrl('work_orders_home'));
    }

    /**
     * @Route("/work/order/process/{id}", name="work_order_process", options={"expose"=true})
     * @param WorkOrder $workOrder
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processAction(Request $request, WorkOrder $workOrder)
    {
        $qty = $workOrder->getQuantity();
        $pdt = $workOrder->getProduct();
        $ins = $workOrder->getInstitution();

        $workOrder->setStatus("PROCESS");
        $this->get('session')->getFlashBag()->add(
            'success',
            'Work Order is Processing Successfully'
        );

        $this->getDoctrine()->getRepository('AppBundle:WorkOrder')->update($workOrder);

        if($workOrder->getStatus()=="PROCESS"){
            $this->processInventoryPlastic($ins, $pdt, $qty);
            $this->processInventoryPin($ins, $pdt, $qty);
        }

        return $this->redirect($this->generateUrl('work_orders_home'));
    }

    /**
     * @Route("/work/order/print/{id}", name="work_order_print", options={"expose"=true})
     * @param WorkOrder $workOrder
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function printAction(Request $request, WorkOrder $workOrder)
    {
        $qty = $workOrder->getQuantity();
        $pdt = $workOrder->getProduct();
        $ins = $workOrder->getInstitution();

        $workOrder->setStatus("PRINT");
        $this->get('session')->getFlashBag()->add(
            'success',
            'Work Order is Printing Successfully'
        );

        $this->getDoctrine()->getRepository('AppBundle:WorkOrder')->update($workOrder);

        if($workOrder->getStatus()=="PRINT"){
            $this->printInventoryPlastic($ins, $pdt, $qty, $workOrder);
            $this->printInventoryPin($ins, $pdt, $qty, $workOrder);
        }

        return $this->redirect($this->generateUrl('work_orders_home'));
    }

//    /**
//     * @Route("/work/order/update/{id}", name="work_order_update", options={"expose"=true})
//     * @Template("@App/WorkOrder/form.html.twig")
//     * @param Request $request
//     * @param WorkOrder $workOrder
//     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function updateAction(Request $request, WorkOrder $workOrder)
//    {
//        $form = $this->createForm(WorkOrderForm::class, $workOrder);
//
//        if ('POST' === $request->getMethod()) {
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//
//                $this->getDoctrine()->getRepository('AppBundle:WorkOrder')->update($workOrder);
//
//                $this->get('session')->getFlashBag()->add(
//                    'success',
//                    'Work Order Update Successfully'
//                );
//
//                return $this->redirect($this->generateUrl('work_orders_home'));
//            }
//        }
//
//        return array(
//            'form' => $form->createView(),
//            'workOrder' => $workOrder
//        );
//    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     */
    public function acceptInventoryPlastic($ins, $pdt, $qty)
    {
        $inventoryPlastic = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $pdt,
        ));
        $inventoryPlastic->setOnLock($qty);
        $inventoryPlastic->setOnHand($inventoryPlastic->getQuantity() - $qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPlastic);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     */
    public function acceptInventoryPin($ins, $pdt, $qty)
    {
        $inventoryPin = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $this->getDoctrine()->getRepository('AppBundle:Product')->findByName('PIN')
        ));

        $inventoryPin->setOnLock($qty);
        $inventoryPin->setOnHand($inventoryPin->getQuantity() - $qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPin);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     * @param $workOrder
     * @param $previousStatus
     */
    public function rejectInventoryPlastic($ins, $pdt, $qty, $workOrder, $previousStatus)
    {
        $inventoryPlastic = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $pdt,
        ));
        if($previousStatus=="PROCESS"){$inventoryPlastic->setQuantity($inventoryPlastic->getQuantity()+$qty);}
        $inventoryPlastic->setOnLock($inventoryPlastic->getOnLock()-$qty);
        $inventoryPlastic->setOnHand($inventoryPlastic->getOnHand() + $qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPlastic);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     * @param $workOrder
     * @param $previousStatus
     */
    public function rejectInventoryPin($ins, $pdt, $qty, $workOrder, $previousStatus)
    {
        $inventoryPin = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $this->getDoctrine()->getRepository('AppBundle:Product')->findByName('PIN')
        ));
        if($previousStatus=="PROCESS"){$inventoryPin->setQuantity($inventoryPin->getQuantity()+$qty);}
        $inventoryPin->setOnLock($inventoryPin->getOnLock()-$qty);
        $inventoryPin->setOnHand($inventoryPin->getOnHand() + $qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPin);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     */
    public function processInventoryPlastic($ins, $pdt, $qty)
    {
        $inventoryPlastic = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $pdt,
        ));
        $inventoryPlastic->setQuantity($inventoryPlastic->getQuantity()-$qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPlastic);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     */
    public function processInventoryPin($ins, $pdt, $qty)
    {
        $inventoryPin = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $this->getDoctrine()->getRepository('AppBundle:Product')->findByName('PIN')
        ));

        $inventoryPin->setQuantity($inventoryPin->getQuantity()-$qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPin);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     * @param $workOrder
     */
    public function printInventoryPlastic($ins, $pdt, $qty, $workOrder)
    {
        $inventoryPlastic = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $pdt,
        ));
        $inventoryPlastic->setOnLock($inventoryPlastic->getOnLock() - $qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPlastic);

        $history = new History();
        $history->setInOut('OUT');
        $history->setWorkOrder($workOrder->getWorkOrderNo());
        $history->setInstitution($ins);
        $history->setQuantity($qty);
        $history->setProduct($pdt);
        $history->setType('PLASTIC');

        $this->getDoctrine()->getRepository('AppBundle:History')->create($history);
    }

    /**
     * @param $ins
     * @param $pdt
     * @param $qty
     * @param $workOrder
     */
    public function printInventoryPin($ins, $pdt, $qty, $workOrder)
    {
        $inventoryPin = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
            'institution' => $ins,
            'product' => $this->getDoctrine()->getRepository('AppBundle:Product')->findByName('PIN')
        ));

        $inventoryPin->setOnLock($inventoryPin->getOnLock() - $qty);
        $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventoryPin);

        $history = new History();
        $history->setInOut('OUT');
        $history->setWorkOrder($workOrder->getWorkOrderNo());
        $history->setInstitution($ins);
        $history->setQuantity($qty);
        $history->setProduct($this->getDoctrine()->getRepository('AppBundle:Product')->findOneBy(array('name' => 'PIN')));
        $history->setType('PIN');

        $this->getDoctrine()->getRepository('AppBundle:History')->create($history);
    }
}
