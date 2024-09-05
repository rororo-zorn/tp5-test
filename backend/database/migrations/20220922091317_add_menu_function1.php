<?php

use think\migration\Migrator;

class AddMenuFunction1 extends Migrator
{
    protected $tableName = 'menu_function';

    /**
     * 插入数据
     * @var array[]
     */
    protected $insertData = [
        // 新增用户
        [104, 'index/operation.NewUser/export', '导出', ''],

        // 渠道管理
        [108, 'index/operation.Channel/add', '新建渠道', ''],
        [108, 'index/operation.Channel/doAdd', '新建渠道操作', ''],
        [108, 'index/operation.Channel/doDelete', '删除渠道操作', ''],
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
     * 获取删除的fun_url
     * @return string
     */
    protected function getDeleteFunUrl()
    {
        $url = [];
        foreach ($this->insertData as $data) {
            $url[] = '"' . $data[1] . '"';
        }
        return implode_comma($url);
    }

    /**
     * 获取down命令执行的sql语句
     * @return string
     */
    protected function getDownCommandExecuteSql()
    {
        $sql = 'DELETE FROM %s WHERE fun_url IN ( %s )';
        return sprintf($sql, config('database.prefix') . $this->tableName, $this->getDeleteFunUrl());
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
