<?php
declare (strict_types = 1);
namespace app\console\controller;

use think\response\Json;
use core\basic\BaseController;
use core\exceptions\ApiException;
use app\services\user\UserServices;
use app\services\user\JwtTokenServices;

class PublicController extends BaseController
{

    /**
     * 用户登录
     * @return Json
     */
    final public function login(): Json
    {
        $ipAddress = $this->request->ip();
        $data = $this->request->post(['name', 'password'], null, 'trim');

        try {
            $this->validate(
                $data,
                ['name' => 'require', 'password' => 'require'],
                ['name.require' => '用户名不得为空', 'password.require' => '密码不得为空']
            );
        } catch (\think\exception\ValidateException $e) {
            throw new ApiException($e->getError());
        }

        /** @var UserServices $userService */
        $userService = $this->app->make(UserServices::class);
        $userInfo = $userService->getOne(['name' => $data['name']], null, ['token']);
        is_null($userInfo) && throw new ApiException('查无此人，用户不存在，请重新输入');
        !$userInfo['status'] && throw new ApiException("用户：${data['name']} 已禁用");
        $data['password'] !== $userInfo['password'] && throw new ApiException('密码验证失败');
        // 更新登录时间和ip地址
        $userService->updateOne($userInfo['id'], ['ipaddress' => ip2long($ipAddress), 'last_login' => time()]);
        // 验证通过后签发token
        $token = app()->make(\core\utils\JwtAuth::class)->createToken($userInfo['id'], $userInfo['name']);

        /** @var JwtTokenServices $jwtService */
        $jwtService = $this->app->make(JwtTokenServices::class);
        // 把token信息同步到数据库
        isset($userInfo['token'])
            ? $jwtService->updateOne($userInfo['id'], ['token' => $token])
            : $jwtService->saveOne(['uid' => $userInfo['id'], 'user' => $userInfo['name'], 'token' => $token]);

        return $this->json->successful('用户登录成功', ['info' => ['uid' => $userInfo['id'], 'name' => $userInfo['name'],'authorization' => $token]]);
    }

    /**
     * 用户登出
     * @return Json
     */
    final public function logout(): Json
    {
        $user = $this->request->post('name/s');
        return $this->json->successful('用户：' . $user . ' 退出登录');
    }

    /**
     * 文件上传
     * @return Json
     */
    final public function upload(): Json
    {
        try {
            $upload = \core\services\UploadService::init();
            // 设置默认值upload是因为ckeditor上传时有固定的name
            $fileField = $this->request->post('field/s', 'upload', 'trim');
            $uploadPath = $this->request->post('path/s', 'images/article', 'trim');
            $fileInfo = $upload->to($uploadPath)->validate()->move($fileField);
            // 适配ckeditor要求返回的参数
            if ('upload' === $fileField) {
                return json(['uploaded' => 1, 'url' => $fileInfo['fullPath']]);
            } else {
                $uploadInfo = ['uid' => $fileInfo['uid'], 'name' => $fileInfo['fileName'], 'url' => $fileInfo['fullPath']];
                return $fileInfo ? $this->json->successful('File uploaded successfully', $uploadInfo) : $this->json->fail('File upload failed');
            }
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * 删除文件
     * @return Json
     */
    final public function removeFile(): Json
    {
        $remove = \core\services\UploadService::init();
        $fileInfo = $remove->delete($this->request->param('filePath/s'));
        return $fileInfo ? $this->json->successful('File deleted successfully') : $this->json->fail('File deleted failed');
    }
}
