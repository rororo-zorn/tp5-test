<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Retention extends Migrator
{
    protected $tableName = 'retention';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('retention_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '留存率类型'])
            ->addColumn('platform', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => true, 'null' => false, 'default' => 1, 'comment' => '平台'])
            ->addColumn('channel', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '渠道'])
            ->addColumn('count', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '对应类型人数'])
            ->addColumn('retention1', 'integer', ['signed' => true, 'null' => false, 'default' => 0, 'comment' => '当日留存'])
            ->addColumn('retention2', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '次日留存'])
            ->addColumn('retention3', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '3日留存'])
            ->addColumn('retention4', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '4日留存'])
            ->addColumn('retention5', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '5日留存'])
            ->addColumn('retention6', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '6日留存'])
            ->addColumn('retention7', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '7日留存'])
            ->addColumn('retention8', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '8日留存'])
            ->addColumn('retention9', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '9日留存'])
            ->addColumn('retention10', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '10日留存'])
            ->addColumn('retention11', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '11日留存'])
            ->addColumn('retention12', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '12日留存'])
            ->addColumn('retention13', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '13日留存'])
            ->addColumn('retention14', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '14日留存'])
            ->addColumn('retention15', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '15日留存'])
            ->addColumn('retention16', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '16日留存'])
            ->addColumn('retention17', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '17日留存'])
            ->addColumn('retention18', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '18日留存'])
            ->addColumn('retention19', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '19日留存'])
            ->addColumn('retention20', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '20日留存'])
            ->addColumn('retention21', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '21日留存'])
            ->addColumn('retention22', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '22日留存'])
            ->addColumn('retention23', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '23日留存'])
            ->addColumn('retention24', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '24日留存'])
            ->addColumn('retention25', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '25日留存'])
            ->addColumn('retention26', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '26日留存'])
            ->addColumn('retention27', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '27日留存'])
            ->addColumn('retention28', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '28日留存'])
            ->addColumn('retention29', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '29日留存'])
            ->addColumn('retention30', 'integer', ['signed' => true, 'null' => false, 'default' => -1, 'comment' => '30日留存'])
            ->addColumn('daily_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '每日时间戳（零点）'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('留存率表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
