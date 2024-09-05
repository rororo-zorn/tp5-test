<?php

use think\migration\Migrator;

class Channel extends Migrator
{
    protected $tableName = 'channel';

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('channel_name', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '渠道名'])
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
