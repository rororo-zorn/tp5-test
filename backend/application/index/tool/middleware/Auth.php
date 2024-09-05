<?php

namespace app\index\tool\middleware;

use app\common\tool\traits\JsonResponse;
use app\common\tool\facade\FacadeUser;
use app\common\model\backend\Menu;
use app\common\model\backend\MenuFunction;

class Auth
{
    use JsonResponse;

    /**
     * 请求url
     * @var
     */
    protected $requestUrl;

    /**
     * 登录url
     * @var string
     */
    protected $loginUrl = 'index/Login/index';

    /**
     * 错误消息
     * @var string
     */
    protected $errorMessage = '警告，无权操作';

    /**
     * 开放权限
     * @var string[]
     */
    protected $publicPrivilege = [
        'index/Login/index',
        'index/Login/doLogin',
    ];

    /**
     * 默认权限
     * @var string[]
     */
    protected $defaultPrivilege = [
        'index/Index/index',
        'index/Index/welcome',
        'index/Index/editPassword',
        'index/Index/doEditPassword',
        'index/Index/doLogout',
    ];

    /**
     * 中间件入口执行方法
     * @param \think\Request $request
     * @param \Closure $next
     * @return mixed|\think\Response|\think\response\Redirect
     */
    public function handle(\think\Request $request, \Closure $next)
    {
        $this->requestUrl = $this->getRequestUrl($request);

        if ($this->isPublicPrivilege()) {
            return $next($request);
        }

        if (!FacadeUser::isLogged()) {
            return redirect($this->loginUrl);
        }

        if ($this->isDefaultPrivilege() || $this->isUserPrivilege()) {
            if ($this->isNotGetServerTimestamp()) {
                FacadeUser::updateSessionTime();
            }

            return $next($request);
        }
        
        return $this->errorResponse($this->errorMessage);
    }

    /**
     * 获取请求url
     * @param \think\Request $request
     * @return string
     */
    protected function getRequestUrl(\think\Request $request)
    {
        $requestUrl = $request->path();
        if (empty($requestUrl)) {
            $requestUrl = implode('/', [$request->module(), $request->controller(), $request->action(true)]);
        }

        return $requestUrl;
    }

    /**
     * 是否是开放权限
     * @return bool
     */
    protected function isPublicPrivilege()
    {
        return in_array($this->requestUrl, $this->publicPrivilege);
    }

    /**
     * 是否是默认权限
     * @return bool
     */
    protected function isDefaultPrivilege()
    {
        return in_array($this->requestUrl, $this->defaultPrivilege);
    }

    /**
     * 是否是用户权限
     * @return bool
     */
    protected function isUserPrivilege()
    {
        return in_array($this->getPrivilegeId(), FacadeUser::getPrivilegeId());
    }

    /**
     * 非请求服务器时间
     * @return bool
     */
    protected function isNotGetServerTimestamp()
    {
        return $this->requestUrl != 'index/Index/getServerTimestamp';
    }

    /**
     * 获取权限id
     * @return false|mixed
     */
    protected function getPrivilegeId()
    {
        $privilegeId = Menu::getMenuIdByUrl($this->requestUrl);

        if ($privilegeId === false) {
            $privilegeId = MenuFunction::getMenuIdByUrl($this->requestUrl);
        }

        return $privilegeId;
    }
}