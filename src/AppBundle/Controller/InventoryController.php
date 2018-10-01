<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\InventoryDatatable;
use AppBundle\Entity\History;
use AppBundle\Entity\Institution;
use AppBundle\Entity\Inventory;
use AppBundle\Entity\Product;
use AppBundle\Form\Search\InstitutionForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InventoryController extends BaseController
{
    /**
     * @Route("/inventories", name="inventories_home", options={"expose"=true})
     * @Template()
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        $data = $request->query->get('');
        $formSearch = $this->createForm(InstitutionForm::class, $data);

        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(InventoryDatatable::class, $request->isXmlHttpRequest(), function($qb)
        {
            /** @var \Doctrine\ORM\QueryBuilder $qb */
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('AppBundle:Inventory:index.html.twig', array(
            'datatable' => $datatable,
            'form'      => $formSearch->createView()
        ));
    }

    /**
     * @Route("/inventory/create", name="inventory_create", options={"expose"=true})
     * @Template("@App/Inventory/form.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        if( $request->request->get('institution')["institution"] == null && $request->get('institution_id') == null ){
            return $this->redirect($this->generateUrl('inventories_home'));
        }

        $institutionID = $request->request->get('institution')["institution"]?$request->request->get('institution')["institution"]:$request->get('institution_id');
        $institution = $this->getDoctrine()->getRepository('AppBundle:Institution')->find($institutionID);
        $inventories = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findByInstitution($institution);

        if (isset($_POST['inv_submit'])) {
            foreach ($inventories as $inventory){
                $qty = $inventory->getQuantity()+$_POST[$inventory->getId()];
                $onHand =  $inventory->getOnHand()+$_POST[$inventory->getId()];
                $inventory->setQuantity($qty);
                $inventory->setOnHand($onHand);
                $this->getDoctrine()->getRepository('AppBundle:Inventory')->update($inventory);

                if($_POST[$inventory->getId()]>0) {
                    $history = new History();
                    $history->setInOut('IN');
                    $history->setWorkOrder("");
                    $history->setInstitution($inventory->getInstitution());
                    $history->setQuantity($qty);
                    $history->setProduct($inventory->getProduct());
                    $history->setType($inventory->getType());

                    $this->getDoctrine()->getRepository('AppBundle:History')->create($history);
                }
            }

            $this->get('session')->getFlashBag()->add(
                'success',
                'Inventory Added Successfully'
            );

            return $this->redirect($this->generateUrl('inventories_home'));
        }

        return array(
            'inventories' => $inventories,
            'institution' => $institution
        );
    }

    /**
     * @Route("/inventory/sync", name="inventory_sync_home", options={"expose"=true})
     * @Template()
     * @param Request $request
     * @return Response
     */
    public function inventorySyncAction(Request $request)
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();
        $institutions = $this->getDoctrine()->getRepository('AppBundle:Institution')->findAll();

        foreach ($institutions as $institution){

            foreach ($products as $product){

                $inv = $this->getDoctrine()->getRepository('AppBundle:Inventory')->findOneBy(array(
                    'product' => $product,
                    'type' => $product->getType(),
                    'institution' => $institution
                ));

                if($inv == null){
                    $inventoryObj = new Inventory();
                    $inventoryObj->setOnLock(0);
                    $inventoryObj->setOnHand(0);
                    $inventoryObj->setQuantity(0);
                    $inventoryObj->setProduct($product);
                    $inventoryObj->setType($product->getType());
                    $inventoryObj->setInstitution($institution);
                    $this->getDoctrine()->getRepository('AppBundle:Inventory')->create($inventoryObj);
                }
            }
        }

        $this->get('session')->getFlashBag()->add(
            'success',
            'Inventory Sync Successfully'
        );

        return $this->redirect($this->generateUrl('inventories_home'));
    }
}
