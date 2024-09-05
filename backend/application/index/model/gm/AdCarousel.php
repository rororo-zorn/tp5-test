<?php

namespace app\index\model\gm;

use app\common\tool\excel\read\Rotation;
use app\common\tool\redis\gm\AdCarousel as redis;
use app\common\tool\pipe\gm\AdCarousel as pipe;

class AdCarousel
{
    /**
     * 广告轮播图配置
     * @var array
     */
    protected $adConfig = [];

    /**
     * layui table clos
     * @return array
     */
    public static function getLayUiTableClos()
    {
        return [
            ['field' => 'name', 'title' => '广告名称'],
            ['field' => 'enable', 'title' => '状态', 'templet' => '#status'],
            ['field' => 'startTime', 'title' => '开始时间'],
            ['field' => 'endTime', 'title' => '截至时间'],
            ['field' => '', 'fixed' => 'right', 'width' => 200, 'align' => 'center', 'toolbar' => '#bar', 'title' => '操作'],
        ];
    }

    public function __construct()
    {
        $this->initAdConfig();
    }

    /**
     * 初始化广告轮播图配置
     * redis配置覆盖配置表
     */
    protected function initAdConfig()
    {
        $excelConfig = (new Rotation())->getAdConfig();
        $redisConfig = (new redis())->getAdConfig();

        foreach ($redisConfig as $key => $value) {
            $value['name'] = isset($excelConfig[$key]) ? $excelConfig[$key]['name'] : '配置表已删除';
            $excelConfig[$key] = $value;
        }

        $this->adConfig = $excelConfig;
    }

    /**
     * 获取广告轮播图配置
     * @return array
     */
    public function getAdConfig()
    {
        return $this->adConfig;
    }

    /**
     * 根据id获取广告轮播图配置
     * @param $id
     * @return bool|mixed
     */
    public function getAdConfigById($id)
    {
        return $this->adConfig[$id] ?? false;
    }

    /**
     * 编辑广告轮播图配置
     * @param $param
     * @return bool
     */
    public static function editAdConfig($param)
    {
        return (new redis())->editAdConfig($param) && (new pipe())->send()->isSuccess();
    }

    /**
     * 删除广告轮播图配置
     * @param $id
     * @return bool
     */
    public static function deleteAdConfig($id)
    {
        return (new redis())->deleteAdConfig($id) && (new pipe())->send()->isSuccess();
    }
}