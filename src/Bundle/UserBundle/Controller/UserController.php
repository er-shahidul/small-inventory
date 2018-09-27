<?php

namespace Bundle\UserBundle\Controller;

use Bundle\UserBundle\Entity\User;
use Bundle\UserBundle\Form\Type\UserForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Bundle\UserBundle\Datatables\UserDatatable;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Bundle\UserBundle\Form\Type\UserUpdatePasswordForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * User Controller.
 *
 */
class UserController extends UserBaseController
{
    /**
     * @Route("/users", name="users_home", options={"expose"=true})
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(UserDatatable::class, $request->isXmlHttpRequest(), function($qb)
        {
            /** @var \Doctrine\ORM\QueryBuilder $qb */
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('UserBundle:User:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/user/create", name="user_create")
     * @Template("UserBundle:User:new.html.twig")
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserForm::class, $user, ['attr' => []]);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $user->setEnabled(true);
                $this->getDoctrine()->getRepository('UserBundle:User')->create($user);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('User Created Successfully!')
                );

                return $this->redirect($this->generateUrl('users_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'mode' => 'create',
            'user' => $user
        );
    }

    /**
     * @Route("/user/update/{id}", name="user_update", options={"expose"=true})
     * @Template("UserBundle:User:new.html.twig")
     * @param Request $request
     * @param User $user
     * @Security("has_role('ROLE_ADMIN')")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, User $user)
    {
        $service = $this->get('user.registration.form.type');
        $service->setLoginUser($this->getUser());
        $form = $this->createForm(UserForm::class, $user, ['attr' => []]);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $this->get('fos_user.user_manager')->updateUser($user);
                $user->getProfile()->preUpload();
                $user->getProfile()->upload();
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('User Updated Successfully!')
                );

                return $this->redirect($this->generateUrl('users_home'));
            }
        }

        return array(
            'form' => $form->createView(),
            'mode' => 'edit',
            'user' => $user
        );
    }

    /**
     * @Route("/user/update/password/{id}", name="user_update_password", options={"expose"=true})
     * @Template("UserBundle:User:update.password.html.twig")
     * @param Request $request
     * @param User $user
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updatePasswordAction(Request $request, User $user)
    {
        $form = $this->createForm(UserUpdatePasswordForm::class, $user);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $user->setPassword($form->get('plainPassword')->getData());
                $user->setPlainPassword($form->get('plainPassword')->getData());

                $this->getDoctrine()->getRepository('UserBundle:User')->update($user);

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    $this->get('translator')->trans('Password Successfully Change')
                );

                return $this->redirect($this->generateUrl('users_home'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/user/enabled/{id}", name="user_enabled", options={"expose"=true})
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function userEnabledAction(User $user)
    {
        $enable = $this->isUserEnabled($user);
        $user->setEnabled($this->isUserEnabled($user));

        $this->getDoctrine()->getRepository('UserBundle:User')->update($user);

        $messageString = $enable ? $this->get('translator')->trans('User Successfully Enable') : $this->get('translator')->trans('User Successfully Disable');
        $this->get('session')->getFlashBag()->add(
            'success',
            $messageString
        );

        return $this->redirect($this->generateUrl('users_home'));
    }

    /**
     * @Route("/user/details/{id}", name="user_details", options={"expose"=true})
     * @Template()
     * @param User $user
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     * @return Response
     */
    public function detailsAction(User $user)
    {
        return $this->render('UserBundle:User:details.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", options={"expose"=true})
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_OFFICE_ADMIN')")
     */
    public function deleteAction(User $user)
    {
        return $this->redirect($this->generateUrl('users_home'));
    }

    private function checkPhoneNumberWithCountryCode($userCellPhoneNumber){

        preg_match( '/(0|\+?\d{2})(\d{7,8})/', $userCellPhoneNumber, $matches);

        if($matches[1] == '+88'){
            return $matches[1];
        } else{
            return false;
        }

    }

    /**
     * @param User $user
     * @return int
     */
    protected function isUserEnabled(User $user)
    {
        if ($user->isEnabled()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @Route("/user/check/username", name="check_username")
     * @param Request $request
     * @Security("has_role('ROLE_MANAGE_USERS')")
     * @return Response
     */
    public function checkUsernameAction(Request $request)
    {
        $username = $request->query->get('user[username]', null, true);
        $manager = $this->get('fos_user.user_manager');
        $response = $manager->findUserByUsername($username) ? 'false' : 'true';

        return new Response($response);
    }

    /**
     * @Route("/user/check/email", name="check_email")
     * @param Request $request
     * @Security("has_role('ROLE_MANAGE_USERS')")
     * @return Response
     */
    public function checkEmailAddresAction(Request $request)
    {
        $email = $request->query->get('user[email]', null, true);
        $manager = $this->get('fos_user.user_manager');
        $response = $manager->findUserByEmail($email) ? 'false' : 'true';

        return new Response($response);
    }

    /**
     * @Route("/user/{id}/tooltip-info", name="user_tooltip_info")
     * @param Request $request
     * @Security("has_role('ROLE_MANAGE_USERS')")
     * @return Response
     */
    public function userInfoAction(Request $request, User $user) {

        return $this->render('UserBundle:User:tooltip_info.html.twig', array('user' => $user));
    }
}