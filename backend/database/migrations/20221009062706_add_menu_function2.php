<?php

use think\migration\Migrator;

class AddMenuFunction2 extends Migrator
{
    protected $tableName = 'menu_function';

    /**
     * 插入数据
     * @var array[]
     */
    protected $insertData = [
        // 留存
        [106, 'index/operation.Retention/exportNewRetention', '导出', ''],
        [110, 'index/operation.Retention/exportActiveRetention', '导出', ''],
        [112, 'index/operation.Retention/exportBankruptcyRetention', '导出', ''],

        // 平台管理
        [109, 'index/operation.Platform/edit', '编辑平台', ''],
        [109, 'index/operation.Platform/doEdit', '编辑平台操作', ''],
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
