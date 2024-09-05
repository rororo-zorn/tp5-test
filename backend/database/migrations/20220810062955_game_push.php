<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class GamePush extends Migrator
{
    protected $tableName = 'game_push';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('push_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '推送类型'])
            ->addColumn('send_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '发送对象'])
            ->addColumn('uid', 'text', ['null' => true, 'comment' => '指定玩家ID'])
            ->addColumn('title', 'string', ['limit' => 256, 'null' => false, 'default' => '', 'comment' => '推送标题'])
            ->addColumn('content', 'string', ['limit' => 1024, 'null' => false, 'default' => '', 'comment' => '推送内容'])
            ->addColumn('image', 'string', ['limit' => 512, 'null' => false, 'default' => '', 'comment' => '推送图片'])
            ->addColumn('start_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '开始时间'])
            ->addColumn('end_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '截止时间'])
            ->addColumn('push_start_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '生效期每天开始推送时间'])
            ->addColumn('push_interval', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '推送间隔'])
            ->addColumn('push_interval_unit', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '推送间隔单位'])
            ->addColumn('push_times', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '推送次数'])
            ->addColumn('add_user', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加人'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('游戏推送表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
