<?php
declare (strict_types = 1);
namespace app\model\auth;

use core\basic\BaseModel;

class AuthModel extends BaseModel
{
    protected $jsonAssoc = true;
    protected $name = 'authMenu';
    protected $json = ['authority'];
}
