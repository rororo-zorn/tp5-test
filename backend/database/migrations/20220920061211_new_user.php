<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class NewUser extends Migrator
{
    protected $tableName = 'new_user';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('platform', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => true, 'null' => false, 'default' => 0, 'comment' => '平台'])
            ->addColumn('channel', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '渠道'])
            ->addColumn('new_user_count', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '新增玩家数量'])
            ->addColumn('daily_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '每日时间戳（零点）'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('新增用户')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
