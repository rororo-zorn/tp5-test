<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Email extends Migrator
{
    protected $tableName = 'email';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('theme', 'text', ['null' => true, 'comment' => '邮件主题'])
            ->addColumn('title', 'text', ['null' => true, 'comment' => '邮件标题'])
            ->addColumn('content', 'text', ['null' => true, 'comment' => '邮件内容'])
            ->addColumn('tail', 'text', ['null' => true, 'comment' => '邮件落款'])
            ->addColumn('item', 'text', ['null' => true, 'comment' => '附件'])
            ->addColumn('send_type', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '发送对象'])
            ->addColumn('uid', 'text', ['null' => true, 'comment' => '指定玩家ID'])
            ->addColumn('send_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '发送时间'])
            ->addColumn('email_status', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 1, 'comment' => '邮件状态（1未发送 2发送成功 3发送失败）'])
            ->addColumn('add_user', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加人'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('邮件表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}