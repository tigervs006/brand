<?php
declare (strict_types = 1);
namespace app\console\controller;

use think\facade\Cache;
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
        !password_verify($data['password'], $userInfo['password']) && throw new ApiException('密码验证失败');
        /* 更新登录时间和ip地址 */
        $userService->updateOne($userInfo['id'], ['ipaddress' => ip2long($ipAddress), 'last_login' => time()]);
        /* 验证通过后签发token */
        $token = app()->make(\core\utils\JwtAuth::class)->createToken($userInfo['id'], $userInfo['name']);

        /** @var JwtTokenServices $jwtService */
        $jwtService = $this->app->make(JwtTokenServices::class);
        /* 把token信息同步到数据库 */
        isset($userInfo['token'])
            ? $jwtService->updateOne($userInfo['id'], ['token' => $token])
            : $jwtService->saveOne(['uid' => $userInfo['id'], 'user' => $userInfo['name'], 'token' => $token]);

        return $this->json->successful('Login successful', ['info' => ['uid' => $userInfo['id'], 'name' => $userInfo['name'], 'avatar' => $userInfo['avatar'], 'authorization' => $token]]);
    }

    /**
     * 用户登出
     * @return Json
     */
    final public function logout(): Json
    {
        $user = $this->request->post('name/s');
        return $this->json->successful('User：' . $user . ' logout');
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
        $fileInfo = $remove->delete($this->request->param('filePath/s', null, 'trim'));
        return $fileInfo ? $this->json->successful('File deleted successfully') : $this->json->fail('File deleted failed');
    }

    /**
     * 表单提交
     * @return Json
     */
    final public function submitForm(): Json
    {
        $post = $this->request->post([
            'city',
            'email',
            'mobile',
            'message',
            'company',
            'position',
            'username',
            'province',
            'district',
        ], null, 'trim');

        // 关键数据验证
        $scene = empty($post['position']) ? 'modal' : 'basic';
        $validator = 'app\console\validate\FormValidator.' . $scene;
        try {
            $this->validate($post, $validator);
        } catch (\think\exception\ValidateException $e) {
            throw new ApiException($e->getError());
        }

        // 留言来源
        $post['source'] = 1;
        // 留言的ip
        $ipAddress = $this->request->ip();
        // 留言时间
        $nowTime = date('Y-m-d H:i:s');
        // ip转int类型
        $post['ipaddress'] = ip2long($ipAddress);
        // 获取留言页面
        $post['page'] = $this->request->header('referer');
        // 组装省市区地址
        empty($post['position']) && $post['address'] = "{$post['province']},{$post['city']},{$post['district']}";

        // 写入到数据库
        $services = $this->app->make(\app\services\user\ClientServices::class);
        $cid = $services->getFieldValue($post['ipaddress'], 'ipaddress', 'id');
        $cid && $post['id'] = $cid; // 相同ip的留言将视为更新留言信息
        $services->saveClient($post, '留言失败！请检查各项信息是否正确填写');

        // 入库后发送邮件
        if ((int) sys_config('mail_service')) {
            // 邮件模板
            $mailBody = isset($post['position'])
                ? /** @lang text */
                <<<TEMPLATE
                    姓名：{$post['username']}<br/>
                    电话：{$post['mobile']}<br/>
                    邮箱：{$post['email']}<br/>
                    ip地址：{$ipAddress}<br/>
                    留言时间：{$nowTime}<br/>
                    留言页面：{$post['page']}<br/>
                    留言信息：{$post['message']}
                TEMPLATE
                : /** @lang text */
                <<<TEMPLATE
                    姓名：{$post['username']}<br/>
                    电话：{$post['mobile']}<br/>
                    邮箱：{$post['email']}<br/>
                    ip地址：{$ipAddress}<br/>
                    留言时间：{$nowTime}<br/>
                    所在城市：{$post['province']}，{$post['city']}，{$post['district']}<br/>
                    公司名称：{$post['company']}<br/>
                    留言页面：{$post['page']}<br/>
                    留言信息：{$post['message']}
                TEMPLATE;
            (new \core\utils\MailHandler())->sendMail($mailBody);
        }

        $message = isset($cid) ? '更新信息成功' : '留言成功';
        return $this->json->successful("{$message}，我们将会在24小时内联系您");
    }

    /**
     * 刷新缓存
     * @return Json
     */
    final public function refreshCache(): Json
    {
        $key = $this->request->post('key/s', null, 'trim');
        Cache::has($key) ? Cache::delete($key) : throw new ApiException('刷新指定的缓存key不存在');
        return $this->json->successful('刷新缓存成功');
    }
}
