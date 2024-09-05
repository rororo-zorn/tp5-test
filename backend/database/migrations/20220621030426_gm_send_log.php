<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class GmSendLog extends Migrator
{
    protected $tableName = 'gm_send_log';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('log_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '日志类型（1邮件 2跑马灯）'])
            ->addColumn('log_content', 'string', ['limit' => 128, 'null' => false, 'default' => '', 'comment' => '日志内容'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('gm发送日志')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}