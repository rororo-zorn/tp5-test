<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class DailyStatistics extends Migrator
{
    protected $tableName = 'daily_statistics';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('platform', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => true, 'null' => false, 'default' => 0, 'comment' => '平台'])
            ->addColumn('channel', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '渠道'])
            ->addColumn('new_user', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'comment' => '新增玩家'])
            ->addColumn('active_user', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'comment' => '活跃玩家'])
            ->addColumn('bankruptcy_user', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'comment' => '破产玩家'])
            ->addColumn('daily_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '每日时间戳（零点）'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('每日统计表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
