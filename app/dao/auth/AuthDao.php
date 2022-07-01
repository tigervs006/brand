<?php
declare (strict_types = 1);
namespace app\dao\auth;

use app\dao\BaseDao;
use app\model\auth\AuthModel;

class AuthDao extends BaseDao
{

    protected function setModel(): string
    {
        return AuthModel::class;
    }
}
