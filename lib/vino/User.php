<?php

namespace vino;

use horses\plugin\auth\AbstractUser;

/**
 * A user
 * @Entity
 */
class User extends AbstractUser
{
    /**
     * @OneToMany(targetEntity="WinesList", mappedBy="user")
     * @var WinesList[]
     */
     protected $lists;
}