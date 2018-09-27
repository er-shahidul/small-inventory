<?php

namespace Bundle\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Bundle\UserBundle\Entity\UserGroup;
use Bundle\UserBundle\Form\Type\GroupForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bundle\UserBundle\Datatables\GroupDatatable;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Group Controller.
 *
 */
class GroupController extends UserBaseController
{
    /**
     * @Route("/groups", name="groups_home", options={"expose"=true})
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(GroupDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('UserBundle:Group:list.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/group/create", name="group_create")
     * @Template()
     * @param Request $request
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function newAction(Request $request)
    {
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $group = new UserGroup('', []);
        $form = $this->createForm(GroupForm::class, $group);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_SUCCESS, $event);

            $this->getDoctrine()->getRepository('UserBundle:UserGroup')->create($group);
            if (null === $response = $event->getResponse()) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Group Created Successfully'
                );
            }

            return $this->redirect($this->generateUrl('groups_home'));
        }

        return $this->render('UserBundle:Group:new.html.twig', array(
            'form' => $form->createview(),
            'mode' => 'create'
        ));
    }

    /**
     * @Route("/group/update/{id}", name="group_update", options={"expose"=true})
     * @Template()
     * @param Request $request
     * @param UserGroup $group
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function groupUpdateAction(Request $request, UserGroup $group)
    {
        $form = $this->createForm(GroupForm::class, $group);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->getDoctrine()->getRepository('UserBundle:UserGroup')->update($group);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Group Updated Successfully'
                );

                return $this->redirect($this->generateUrl('groups_home'));
            }
        }

        return $this->render('UserBundle:Group:new.html.twig', array(
            'form' => $form->createview(),
            'mode' => 'edit'
        ));
    }

    /**
     * @Route("/group/delete/{id}", name="group_delete", options={"expose"=true})
     * @Template()
     * @param Request $request
     * @param UserGroup $group
     * @return RedirectResponse
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function groupDeleteAction(Request $request, UserGroup $group)
    {
        $this->container->get('fos_user.group_manager')->deleteGroup($group);

        $response = new RedirectResponse($this->container->get('router')->generate('groups_home'));

        $this->get('session')->getFlashBag()->add(
            'success',
            'Group Deleted Successfully'
        );

        return $response;
    }
}
