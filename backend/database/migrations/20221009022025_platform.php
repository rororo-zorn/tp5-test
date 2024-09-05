<?php

use think\migration\Migrator;

class Platform extends Migrator
{
    protected $tableName = 'platform';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('platform_name', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '平台名'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('渠道表')
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
