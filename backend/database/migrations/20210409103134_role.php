<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Role extends Migrator
{
    protected $tableName = 'role';

    /**
     * 默认数据，必须是二维数组
     * @var array[]
     */
    protected $defaultData = [
        [
            'id' => 1,
            'role_name' => '超级管理员_',
            'secret' => 'P3VZRY57KS5EEBEL',
            'is_super' => 1,
            'user_num' => 1,
            'add_time' => '1615534737'
        ]
    ];

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('role_name', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '角色名'])
            ->addColumn('secret', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '谷歌秘钥'])
            ->addColumn('is_super', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '是否为超级用户角色（0否 1是）'])
            ->addColumn('user_num', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '账号个数'])
            ->addColumn('privilege_id', 'string', ['limit' => 512, 'null' => false, 'default' => '[]', 'comment' => '权限id（菜单id）'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->addIndex('id')
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('角色表')
            ->setData($this->defaultData)
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
