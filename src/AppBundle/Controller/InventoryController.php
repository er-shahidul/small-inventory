<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\InventoryDatatable;
use AppBundle\Entity\Inventory;
use AppBundle\Entity\Product;
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
        ));
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
