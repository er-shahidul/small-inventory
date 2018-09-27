<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\ProductDatatable;
use AppBundle\Entity\Product;
use AppBundle\Form\Type\ProductForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductController extends BaseController
{
    /**
     * @Route("/products", name="products_home", options={"expose"=true})
     * @Template()
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(ProductDatatable::class, $request->isXmlHttpRequest(), function($qb)
        {
            /** @var \Doctrine\ORM\QueryBuilder $qb */
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('AppBundle:Product:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/product/create", name="product_create", options={"expose"=true})
     * @Template("@App/Product/form.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $this->getDoctrine()->getRepository('AppBundle:Product')->create($product);

            $this->get('session')->getFlashBag()->add(
                'success',
                'Product Create Successfully'
            );

            return $this->redirect($this->generateUrl('products_home'));
        }

        return array(
            'form' => $form->createView()
        );
    }
}
