<?php
declare (strict_types = 1);
namespace app\services\user;

use app\dao\user\UserDao;
use app\services\BaseServices;
use core\exceptions\ApiException;

class UserServices extends BaseServices
{
    public function __construct(UserDao $dao)
    {
        $this->dao = $dao;
    }

}