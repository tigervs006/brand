<?php
declare (strict_types = 1);
namespace app\console\controller;

use think\response\Json;
use core\basic\BaseController;
use core\exceptions\AuthException;
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
        $data = $this->request->post(['name', 'password'], null, 'trim');

        try {
            $this->validate(
                $data,
                ['name' => 'require', 'password' => 'require'],
                ['name.require' => '用户名不得为空', 'password.require' => '密码不得为空']
            );
        } catch (\think\exception\ValidateException $e) {
            throw new AuthException($e->getError());
        }

        /** @var UserServices $userService */
        $userService = $this->app->make(UserServices::class);
        $userInfo = $userService->getOne(['name' => $data['name']], null, ['token']);
        is_null($userInfo) && throw new AuthException('查无此人，用户不存在');
        !$userInfo['status'] && throw new AuthException("用户：${data['name']} >>已禁用");
        $data['password'] !== $userInfo['password'] && throw new AuthException('密码验证失败');
        $token = app()->make(\core\utils\JwtAuth::class)->createToken($userInfo['id'], $userInfo['name']);

        /** @var JwtTokenServices $jwtService */
        $jwtService = $this->app->make(JwtTokenServices::class);
        // 把token信息写入到数据库
        isset($userInfo['token'])
            ? $jwtService->updateOne($userInfo['id'], ['token' => $token])
            : $jwtService->saveOne(['uid' => $userInfo['id'], 'user' => $userInfo['name'], 'token' => $token]);

        $info = ['uid' => $userInfo['id'], 'name' => $userInfo['name'],'authorization' => $token];
        return $this->json->successful('用户登录成功', compact('info'));
    }

    /**
     * 用户登出
     * @return Json
     */
    final public function logout(): Json
    {
        $user = $this->request->post('name/s');
        return $this->json->successful('用户：' . $user . ' >> 退出登录');
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
            $fileField = $this->request->param('field/s', 'upload');
            $fileInfo = $upload->to('routine/product')->validate()->move($fileField);
            // 适配ckeditor要求返回的参数
            if ('upload' === $fileField) {
                return json(['uploaded' => 1, 'url' => $fileInfo['fullPath']]);
            } else {
                $uploadInfo = ['uid' => $fileInfo['uid'], 'name' => $fileInfo['fileName'], 'url' => $fileInfo['fullPath']];
                return $fileInfo ? $this->json->successful('File uploaded successfully', $uploadInfo) : $this->json->fail('File upload failed');
            }
        } catch (\Exception $e) {
            return $this->json->fail($e->getMessage());
        }
    }

    /**
     * 删除文件
     * @return Json
     */
    final public function remove(): Json
    {
        $remove = \core\services\UploadService::init();
        $fileInfo = $remove->delete($this->request->param('filePath/s'));
        return $fileInfo ? $this->json->successful('File deleted successfully') : $this->json->fail('File deleted failed');
    }
}
