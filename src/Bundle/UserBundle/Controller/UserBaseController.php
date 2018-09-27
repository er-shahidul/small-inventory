<?php

namespace Bundle\UserBundle\Controller;

use AppBundle\Controller\BaseController;

class UserBaseController extends BaseController
{
    protected function userRepo()
    {
        return $this->getRepository('UserBundle:User');
    }

    protected function groupRepo()
    {
        return $this->getRepository('UserBundle:UserGroup');
    }
}
