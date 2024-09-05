<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Marquee extends Migrator
{
    protected $tableName = 'marquee';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('marquee_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '跑马灯类型'])
            ->addColumn('content', 'text', ['null' => true, 'comment' => '跑马灯内容'])
            ->addColumn('start_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '开始时间'])
            ->addColumn('end_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '截止时间'])
            ->addColumn('broadcast_start_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '生效期每天开始播报时间'])
            ->addColumn('broadcast_interval', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '播报间隔（单位：分钟）'])
            ->addColumn('broadcast_times', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '播报次数'])
            ->addColumn('remark', 'string', ['limit' => 64, 'null' => false, 'default' => '', 'comment' => '备注（64个字符以内）'])
            ->addColumn('add_user', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加人'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('跑马灯表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
