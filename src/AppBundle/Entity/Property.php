<?php

namespace AppBundle\Entity;


class Property
{
    const VISA = "VISA";
    const MASTER = "MASTER";

    const GROUP_TYPE = [
        'USER' => 'USER',
        'ADMIN' => 'ADMIN',
        'SUPER_ADMIN' => 'SUPER_ADMIN',
    ];
}