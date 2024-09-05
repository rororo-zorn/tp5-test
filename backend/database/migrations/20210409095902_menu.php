<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Menu extends Migrator
{
    protected $tableName = 'menu';

    /**
     * 默认数据，必须是二维数组
     * @var array
     */
    protected $defaultData = [
        // 系统管理
        [1, 0, '系统管理', '', 1, 'layui-icon-set', 1],
        [2, 1, '角色管理', 'index/system.Role/index', 1, '', 1],
        [3, 1, '用户管理', 'index/system.User/index', 2, '', 1],
        [4, 1, '菜单管理', 'index/system.Menu/index', 3, '', 1],

        // 运营数据
        [101, 0, '运营数据', '', 3, 'layui-icon-list', 1],

        // GM工具
        [201, 0, 'GM工具', '', 4, 'layui-icon-util', 1],
        [202, 201, '公告', '', 1, '', 1],
        [203, 202, '游戏内公告', 'index/gm.Notice/index', 1, '', 1],
        [204, 202, '登录公告', 'index/gm.LoginNotice/index', 2, '', 1],
        [205, 201, '邮件', 'index/gm.Email/index', 2, '', 1],
        [206, 201, '跑马灯', 'index/gm.Marquee/index', 3, '', 1],
        [207, 201, '黑白名单', '', 4, '', 1],
        [208, 207, '黑名单', 'index/gm.Blacklist/index', 1, '', 1],
        [209, 207, '白名单', 'index/gm.Whitelist/index', 2, '', 1],
        [210, 201, '启停服务器', 'index/gm.ServerControl/index', 5, '', 1],
        [211, 201, '游戏推送', 'index/gm.GamePush/index', 6, '', 1],
        [212, 201, '游戏开关', '', 7, '', 1],
        [213, 212, '机台', 'index/gm.GameSwitch/showGameSwitch', 1, '', 1],
        [214, 212, '大奖机制', 'index/gm.GameSwitch/showBigWinSwitch', 2, '', 1],
        [215, 212, 'Jackpot', 'index/gm.GameSwitch/showJackpotSwitch', 3, '', 1],
        [216, 212, '广告变现', 'index/gm.GameSwitch/showAdSwitch', 4, '', 1],
        [217, 212, '兑换码', 'index/gm.GameSwitch/showRedeemCodeSwitch', 5, '', 1],
        [218, 212, '排行榜', 'index/gm.GameSwitch/showLeaderBoardSwitch', 6, '', 1],
        [219, 212, '通行证', 'index/gm.GameSwitch/showPassSwitch', 7, '', 1],
        [220, 212, '周常活动', 'index/gm.GameSwitch/showWeeklyActivitySwitch', 8, '', 1],
        [221, 212, '赢分活动', 'index/gm.EarnPoint/index', 9, '', 1],
        [222, 212, '折扣礼包', 'index/gm.GameSwitch/showDiscountPackSwitch', 10, '', 1],
        [223, 212, '敬请期待', 'index/gm.GameSwitch/showStayTunedSwitch', 11, '', 1],
        [224, 212, '在线领金币', 'index/gm.GameSwitch/showGetCoinsOnlineSwitch', 11, '', 1],
        [225, 212, '存钱罐', 'index/gm.GameSwitch/showPiggyBankSwitch', 12, '', 1],
        [226, 212, '7日登录', 'index/gm.GameSwitch/showSevenDayLoginSwitch', 13, '', 1],
        [227, 201, '游戏版本配置', 'index/gm.GameVersion/index', 8, '', 1],
    ];

    /**
     * 获取默认插入数据
     * @return array
     */
    protected function getDefaultInsertData()
    {
        $insertData = [];
        foreach ($this->defaultData as $data) {
            $insertData[] = [
                'id' => $data[0],
                'parent_id' => $data[1],
                'menu_name' => $data[2],
                'menu_url' => $data[3],
                'menu_sort' => $data[4],
                'menu_icon' => $data[5],
                'add_time' => $data[6]
            ];
        }

        return $insertData;
    }

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('parent_id', 'integer', ['signed' => false, 'null' => false, 'default' => '0', 'comment' => '菜单父级id（0表示顶级菜单）'])
            ->addColumn('menu_name', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '菜单名称'])
            ->addColumn('menu_url', 'string', ['limit' => 128, 'null' => false, 'default' => '', 'comment' => '菜单url'])
            ->addColumn('menu_sort', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'signed' => false, 'null' => false, 'default' => 1, 'comment' => '菜单排序（范围1~99）'])
            ->addColumn('menu_icon', 'string', ['limit' => 32, 'null' => false, 'default' => '', 'comment' => '菜单icon（仅适用于顶级菜单）'])
            ->addColumn('add_time', 'integer', ['signed' => false, 'null' => false, 'default' => 0, 'comment' => '添加时间'])
            ->addIndex('id')
            ->addIndex('menu_url')
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('后台菜单表')
            ->setData($this->getDefaultInsertData())
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
