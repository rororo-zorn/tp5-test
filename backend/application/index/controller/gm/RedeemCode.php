<?php

namespace app\index\controller\gm;

use app\common\controller\BaseController;
use app\common\model\game\Coupon;
use app\common\model\game\CouponHistory;
use app\index\validate\gm\RedeemCode as validate;

class RedeemCode extends BaseController
{
    /**
     * 显示兑换码
     * @return mixed|\think\response
     */
    public function index()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/redeem_code/index', ['model' => new Coupon()]);
        }

        $paginator = Coupon::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }

    /**
     * 显示奖励
     * @return mixed
     */
    public function showItem()
    {
        $param = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('showItem')->check($param)) {
            return $this->errorResponse($validate->getError());
        }

        $data = Coupon::getItemById($param['id']);
        return $data === false ? $this->errorResponse() : $this->fetch('gm/redeem_code/show_item', ['data' => $data]);
    }

    /**
     * 添加兑换码
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('gm/redeem_code/add', ['model' => new Coupon()]);
    }

    /**
     * 添加兑换奖励
     * @return mixed
     */
    public function addItem()
    {
        return $this->fetch('gm/redeem_code/add_item', ['item' => Coupon::getItem()]);
    }

    /**
     * 添加兑换码
     * @return \think\Response
     */
    public function doAdd()
    {
        $param = $this->request->param();

        $validate = new validate();
        if (!$validate->scene('doAdd')->check($param)) {
            return $this->errorResponse($validate->getError());
        }

        $result = Coupon::addCoupon($param);
        return $this->jsonResponse($result);
    }

    /**
     * 显示兑换详情
     * @return mixed|\think\response
     */
    public function exchangeDetail()
    {
        if (!$this->request->isAjax()) {
            return $this->fetch('gm/redeem_code_history/index', ['tableClos' => CouponHistory::getLayUiTableClos()]);
        }

        $paginator = CouponHistory::getPaginationData($this->request->param());
        return $this->returnToLayui($paginator);
    }
}
