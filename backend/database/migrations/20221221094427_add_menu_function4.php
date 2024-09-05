<?php

use think\migration\Migrator;

class AddMenuFunction4 extends Migrator
{
    protected $tableName = 'menu_function';

    /**
     * 插入数据
     * @var array[]
     */
    protected $insertData = [
        // 轮播图配置
        [228, 'index/gm.AdCarousel/edit', '编辑轮播图配置', ''],
        [228, 'index/gm.AdCarousel/doEdit', '编辑轮播图配置', ''],
        [228, 'index/gm.AdCarousel/doDelete', '删除轮播图配置', ''],

        // 兑换码
        [228, 'index/gm.RedeemCode/showItem', '查看奖励', ''],
        [228, 'index/gm.RedeemCode/add', '新建兑换码', ''],
        [228, 'index/gm.RedeemCode/addItem', '新建奖励', ''],
        [228, 'index/gm.RedeemCode/doAdd', '新建兑换码', ''],
    ];

    /**
     * 获取插入数据
     * @return array
     */
    protected function getInsertData()
    {
        $insertData = [];
        foreach ($this->insertData as $data) {
            $insertData[] = [
                'menu_id' => $data[0],
                'fun_url' => $data[1],
                'fun_name' => $data[2],
                'fun_des' => $data[3],
            ];
        }

        return $insertData;
    }

    /**
     * 获取删除的id
     * @return string
     */
    protected function getDeleteId()
    {
        $id = [];
        foreach ($this->insertData as $data) {
            $id[] = $data[0];
        }

        return implode_comma($id);
    }

    /**
     * 获取down命令执行的sql语句
     * @return string
     */
    protected function getDownCommandExecuteSql()
    {
        $sql = 'DELETE FROM %s WHERE menu_id IN ( %s )';
        return sprintf($sql, config('database.prefix') . $this->tableName, $this->getDeleteId());
    }

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->insert($this->getInsertData())->saveData();
    }

    public function down()
    {
        $this->execute($this->getDownCommandExecuteSql());
    }
}