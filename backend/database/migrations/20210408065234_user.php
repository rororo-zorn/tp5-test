<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class User extends Migrator
{
    protected $tableName = 'user';

    /**
     * 默认数据，必须为二维数组
     * @var array[]
     */
    protected $defaultData = [
        [
            'id' => 1,
            'username' => 'admin',
            'hash_password' => '$2y$10$Y2lmQpECBCdpLl3JZoxdY.NH/owcYsTA24oz42oFts1J6KRBwJpy.',
            'role_id' => 1,
            'status' => 1,
            'add_time' => 1615534737
        ]
    ];

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('username', 'string', ['limit' => 64, 'null' => false, 'default' => '', 'comment' => '用户账号'])
            ->addColumn('hash_password', 'string', ['limit' => 256, 'null' => false, 'default' => '', 'comment' => '哈希密码'])
            ->addColumn('role_id', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '角色id'])
            ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 1, 'comment' => '状态（0禁止登录 1正常）'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->addColumn('delete_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '软删除时间'])
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('后台账号表')
            ->setData($this->defaultData)
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
