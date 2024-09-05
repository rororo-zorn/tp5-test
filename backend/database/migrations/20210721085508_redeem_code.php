<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class RedeemCode extends Migrator
{
    protected $tableName = 'redeem_code';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('redeem_code_id', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '兑换码ID'])
            ->addColumn('remark', 'string', ['limit' => 64, 'null' => false, 'default' => '', 'comment' => '备注（64个字符以内）'])
            ->addColumn('gift_pack_id', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '礼包ID'])
            ->addColumn('start_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '开始时间'])
            ->addColumn('end_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '截止时间'])
            ->addColumn('receive_limit', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '领取限制（0无限制 1有限制）'])
            ->addColumn('star_lower_limit', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '星级下限（0无限制）'])
            ->addColumn('star_upper_limit', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '星级上限（0无限制）'])
            ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '状态'])
            ->addColumn('receiver_id', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '领取人'])
            ->addColumn('add_user', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加人'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('兑换码表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
