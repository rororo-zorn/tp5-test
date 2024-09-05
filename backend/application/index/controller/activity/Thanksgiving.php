<?php

namespace app\index\controller\activity;

use app\common\controller\BaseController;
use app\index\model\activity\Thanksgiving as model;
use app\index\model\activity\thanksgiving\DiscountGiftPackage;
use app\index\model\activity\thanksgiving\GoldCharge;
use app\index\model\activity\thanksgiving\OpenMoneyBank;
use app\index\validate\activity\Thanksgiving as validate;

class Thanksgiving extends BaseController
{
    /**
     * 折扣礼包
     * @return mixed
     */
    public function discountGiftPackage()
    {
        return $this->fetch('activity/thanksgiving/discountGiftPackage', ['model' => new DiscountGiftPackage()]);
    }

    /**
     * 充值
     * @return mixed
     */
    public function goldCharge()
    {
        return $this->fetch('activity/thanksgiving/goldCharge', ['model' => new GoldCharge()]);
    }

    /**
     * 开启存钱罐
     * @return mixed
     */
    public function openMoneyBank()
    {
        return $this->fetch('activity/thanksgiving/openMoneyBank', ['model' => new OpenMoneyBank()]);
    }

    /**
     * 开启/关闭 折扣礼包活动
     * @return \think\Response
     */
    public function doAddDiscountGiftPackage()
    {
        return $this->deal(new DiscountGiftPackage());
    }

    /**
     * 开启/关闭 充值活动
     * @return \think\Response
     */
    public function doAddGoldCharge()
    {
        return $this->deal(new GoldCharge());
    }

    /**
     * 开启/关闭 开启存钱罐活动
     * @return \think\Response
     */
    public function doAddOpenMoneyBank()
    {
        return $this->deal(new OpenMoneyBank());
    }

    /**
     * @param model $model
     * @return \think\Response
     */
    protected function deal($model)
    {
        $requestParam = $this->request->param();

        // 优先验证活动状态
        if (!in_array($requestParam['isOpen'] , $model::getIsOpenType())) {
            return $this->errorResponse('活动状态，非法参数');
        }

        $validate = new validate();
        if (!$validate->setScene($model, $requestParam['isOpen'])->check($requestParam)) {
            return $this->errorResponse($validate->getError());
        }

        $result = $model->deal($requestParam);
        return $this->jsonResponse($result);
    }
}
