<?php

use think\migration\Migrator;

class AddMenu4 extends Migrator
{
    protected $tableName = 'menu';

    /**
     * 插入数据
     * @var array[]
     */
    protected $insertData = [
        // 运营活动
        [601, 0, '运营活动', '', 8, 'layui-icon-app', 1],
        [602, 601, '感恩节活动', '', 1, '', 1],
        [603, 602, '折扣礼包', 'index/activity.Thanksgiving/discountGiftPackage', 1, '', 1],
        [604, 602, '充值', 'index/activity.Thanksgiving/goldCharge', 2, '', 1],
        [605, 602, '开启存钱罐', 'index/activity.Thanksgiving/openMoneyBank', 3, '', 1],
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
                'id' => $data[0],
                'parent_id' => $data[1],
                'menu_name' => $data[2],
                'menu_url' => $data[3],
                'menu_sort' => $data[4],
                'menu_icon' => $data[5],
                'add_time' => $data[6]
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
        $sql = 'DELETE FROM %s WHERE id IN ( %s )';
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
