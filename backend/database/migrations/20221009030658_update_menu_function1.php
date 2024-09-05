<?php

use think\migration\Migrator;

class UpdateMenuFunction1 extends Migrator
{
    protected $tableName = 'menu_function';

    /**
     * 更新数据
     * @var array[]
     */
    protected $deleteData = [
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
        foreach ($this->deleteData as $data) {
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
     * 获取down命令执行的sql语句
     * @return string
     */
    protected function getDownCommandExecuteSql()
    {
        $sql = 'DELETE FROM %s WHERE menu_id = 108';
        return sprintf($sql, config('database.prefix') . $this->tableName);
    }

    public function up()
    {
        $this->execute($this->getDownCommandExecuteSql());
    }

    public function down()
    {
        $table = $this->table($this->tableName);
        $table->insert($this->getInsertData())->saveData();
    }
}
