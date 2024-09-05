<?php

use think\migration\Migrator;

class MenuFunction extends Migrator
{
    protected $tableName = 'menu_function';

    /**
     * 默认数据，必须为二维数组
     * @var array[]
     */
    protected $defaultData = [
        // 角色管理
        [2, 'index/system.Role/add', '新建角色', ''],
        [2, 'index/system.Role/doAdd', '新建角色操作', ''],
        [2, 'index/system.Role/editRoleName', '编辑名称', ''],
        [2, 'index/system.Role/doEditRoleName', '编辑名称操作', ''],
        [2, 'index/system.Role/getGoogleQRCodeUrl', '身份验证', ''],
        [2, 'index/system.Role/showPrivilege', '权限', ''],
        [2, 'index/system.Role/getPrivilege', '获取权限操作', ''],
        [2, 'index/system.Role/doEditPrivilege', '权限操作', ''],
        [2, 'index/system.Role/doDelete', '删除操作', ''],

        // 用户管理
        [3, 'index/system.User/addUser', '新建用户', ''],
        [3, 'index/system.User/doAddUser', '新建用户操作', ''],
        [3, 'index/system.User/editUsername', '编辑名称', ''],
        [3, 'index/system.User/doEditUsername', '编辑名称操作', ''],
        [3, 'index/system.User/editRoleType', '编辑角色', ''],
        [3, 'index/system.User/doEditRoleType', '编辑角色操作', ''],
        [3, 'index/system.User/editStatus', '编辑状态', ''],
        [3, 'index/system.User/doEditStatus', '编辑状态操作', ''],
        [3, 'index/system.User/doDeleteUser', '删除操作', ''],

        // 菜单管理
        [4, 'index/system.Menu/edit', '编辑', ''],
        [4, 'index/system.Menu/doEdit', '编辑操作', ''],

        // 游戏内公告
        [203, 'index/gm.Notice/add', '新建公告', ''],
        [203, 'index/gm.Notice/addPicture', '新建图片', ''],
        [203, 'index/gm.Notice/doAdd', '新建公告', ''],
        [203, 'index/gm.Notice/edit', '编辑公告', ''],
        [203, 'index/gm.Notice/doEdit', '编辑公告', ''],
        [203, 'index/gm.Notice/doDelete', '删除公告', ''],

        // 登录公告
        [204, 'index/gm.LoginNotice/doAdd', '新建公告', ''],
        [204, 'index/gm.LoginNotice/doDelete', '删除公告', ''],

        // 邮件
        [205, 'index/gm.Email/add', '新建邮件', ''],
        [205, 'index/gm.Email/doAdd', '新建邮件', ''],
        [205, 'index/gm.Email/addItem', '新建附件', ''],
        [205, 'index/gm.Email/showItem', '查看附件', ''],
        [205, 'index/gm.Email/edit', '编辑邮件', ''],
        [205, 'index/gm.Email/doEdit', '编辑邮件', ''],
        [205, 'index/gm.Email/doDelete', '删除邮件', ''],

        // 跑马灯
        [206, 'index/gm.Marquee/add', '新建跑马灯', ''],
        [206, 'index/gm.Marquee/doAdd', '新建跑马灯', ''],
        [206, 'index/gm.Marquee/edit', '编辑跑马灯', ''],
        [206, 'index/gm.Marquee/doEdit', '编辑跑马灯', ''],
        [206, 'index/gm.Marquee/doDelete', '删除跑马灯', ''],

        // 黑名单
        [208, 'index/gm.Blacklist/add', '新建黑名单', ''],
        [208, 'index/gm.Blacklist/doAdd', '新建黑名单', ''],
        [208, 'index/gm.Blacklist/doDelete', '删除黑名单', ''],

        // 白名单
        [209, 'index/gm.Whitelist/add', '新建白名单', ''],
        [209, 'index/gm.Whitelist/doAdd', '新建白名单', ''],
        [209, 'index/gm.Whitelist/doDelete', '删除白名单', ''],

        // 启动服务器
        [210, 'index/gm.ServerControl/start', '开启服务器', ''],
        [210, 'index/gm.ServerControl/stop', '关闭服务器', ''],

        // 游戏推送
        [211, 'index/gm.GamePush/add', '新建游戏推送', ''],
        [211, 'index/gm.GamePush/doAdd', '新建游戏推送操作', ''],
        [211, 'index/gm.GamePush/edit', '编辑游戏推送', ''],
        [211, 'index/gm.GamePush/doEdit', '编辑游戏推送操作', ''],
        [211, 'index/gm.GamePush/doDelete', '删除游戏推送操作', ''],

        // 机台开关
        [213, 'index/gm.GameSwitch/getGameSwitch', '获取机台开关', ''],
        [213, 'index/gm.GameSwitch/openGameSwitch', '开启机台', ''],
        [213, 'index/gm.GameSwitch/closeGameSwitch', '关闭机台', ''],

        // 大奖机制开关
        [214, 'index/gm.GameSwitch/getBigWinSwitch', '获取大奖机制开关', ''],
        [214, 'index/gm.GameSwitch/openBigWinSwitch', '开启大奖机制', ''],
        [214, 'index/gm.GameSwitch/closeBigWinSwitch', '关闭大奖机制', ''],

        // Jackpot开关
        [215, 'index/gm.GameSwitch/getJackpotSwitch', '获取Jackpot开关', ''],
        [215, 'index/gm.GameSwitch/openJackpotSwitch', '开启Jackpot', ''],
        [215, 'index/gm.GameSwitch/closeJackpotSwitch', '关闭Jackpot', ''],

        // Ad开关
        [216, 'index/gm.GameSwitch/getAdSwitch', '获取Ad开关', ''],
        [216, 'index/gm.GameSwitch/openAdSwitch', '开启Ad', ''],
        [216, 'index/gm.GameSwitch/closeAdSwitch', '关闭Ad', ''],

        // 兑换码开关
        [217, 'index/gm.GameSwitch/getRedeemCodeSwitch', '获取兑换码开关', ''],
        [217, 'index/gm.GameSwitch/openRedeemCodeSwitch', '开启兑换码', ''],
        [217, 'index/gm.GameSwitch/closeRedeemCodeSwitch', '关闭兑换码', ''],

        // 排行榜开关
        [218, 'index/gm.GameSwitch/getLeaderBoardSwitch', '获取排行榜开关', ''],
        [218, 'index/gm.GameSwitch/openLeaderBoardSwitch', '开启排行榜', ''],
        [218, 'index/gm.GameSwitch/closeLeaderBoardSwitch', '关闭排行榜', ''],

        // 通行证开关
        [219, 'index/gm.GameSwitch/getPassSwitch', '获取通行证开关', ''],
        [219, 'index/gm.GameSwitch/openPassSwitch', '开启通行证', ''],
        [219, 'index/gm.GameSwitch/closePassSwitch', '关闭通行证', ''],

        // 周常活动开关
        [220, 'index/gm.GameSwitch/getWeeklyActivitySwitch', '获取周常活动开关', ''],
        [220, 'index/gm.GameSwitch/openWeeklyActivitySwitch', '开启周常活动', ''],
        [220, 'index/gm.GameSwitch/closeWeeklyActivitySwitch', '关闭周常活动', ''],

        // 赢分活动
        [221, 'index/gm.EarnPoint/addThisWeekConfig', '新建本周配置', ''],
        [221, 'index/gm.EarnPoint/doAddThisWeekConfig', '新建本周配置操作', ''],
        [221, 'index/gm.EarnPoint/showThisWeekConfig', '显示本周配置', ''],
        [221, 'index/gm.EarnPoint/editThisWeekConfig', '编辑本周配置', ''],
        [221, 'index/gm.EarnPoint/doEditThisWeekConfig', '编辑本周配置操作', ''],
        [221, 'index/gm.EarnPoint/addNextWeekConfig', '新建下周配置', ''],
        [221, 'index/gm.EarnPoint/doAddNextWeekConfig', '新建下周配置操作', ''],
        [221, 'index/gm.EarnPoint/showNextWeekConfig', '显示下周配置', ''],
        [221, 'index/gm.EarnPoint/editNextWeekConfig', '编辑下周配置', ''],
        [221, 'index/gm.EarnPoint/doEditNextWeekConfig', '编辑下周配置操作', ''],

        // 折扣礼包开关
        [222, 'index/gm.GameSwitch/getDiscountPackSwitch', '获取折扣礼包开关', ''],
        [222, 'index/gm.GameSwitch/openDiscountPackSwitch', '开启折扣礼包', ''],
        [222, 'index/gm.GameSwitch/closeDiscountPackSwitch', '关闭折扣礼包', ''],

        // 敬请期待开关
        [223, 'index/gm.GameSwitch/getStayTunedSwitch', '获取敬请期待开关', ''],
        [223, 'index/gm.GameSwitch/openStayTunedSwitch', '开启敬请期待', ''],
        [223, 'index/gm.GameSwitch/closeStayTunedSwitch', '关闭敬请期待', ''],

        // 在线领金币开关
        [224, 'index/gm.GameSwitch/getGetCoinsOnlineSwitch', '获取在线领金币开关', ''],
        [224, 'index/gm.GameSwitch/openGetCoinsOnlineSwitch', '开启在线领金币', ''],
        [224, 'index/gm.GameSwitch/closeGetCoinsOnlineSwitch', '关闭在线领金币', ''],

        // 存钱罐开关
        [225, 'index/gm.GameSwitch/getPiggyBankSwitch', '获取存钱罐开关', ''],
        [225, 'index/gm.GameSwitch/openPiggyBankSwitch', '开启存钱罐', ''],
        [225, 'index/gm.GameSwitch/closePiggyBankSwitch', '关闭存钱罐', ''],

        // 7日登录
        [226, 'index/gm.GameSwitch/getSevenDayLoginSwitch', '获取7日登录开关', ''],
        [226, 'index/gm.GameSwitch/openSevenDayLoginSwitch', '开启7日登录', ''],
        [226, 'index/gm.GameSwitch/closeSevenDayLoginSwitch', '关闭7日登录', ''],

        // 游戏版本控制
        [227, 'index/gm.GameVersion/androidAuditConfig', 'android审核服配置', ''],
        [227, 'index/gm.GameVersion/iosAuditConfig', 'ios审核服配置', ''],
        [227, 'index/gm.GameVersion/androidConfig', 'android正式服配置', ''],
        [227, 'index/gm.GameVersion/iosConfig', 'ios正式服配置', ''],
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
                'menu_id' => $data[0],
                'fun_url' => $data[1],
                'fun_name' => $data[2],
                'fun_des' => $data[3],
            ];
        }

        return $insertData;
    }

    public function up()
    {
        $table = $this->table($this->tableName);
        $table->addColumn('menu_id', 'integer', ['signed' => false, 'null' => false, 'default' => '0', 'comment' => '功能所属菜单id'])
            ->addColumn('fun_url', 'string', ['limit' => 128, 'null' => false, 'default' => '', 'comment' => '功能url'])
            ->addColumn('fun_name', 'string', ['limit' => 16, 'null' => false, 'default' => '', 'comment' => '功能名称（按钮名称）'])
            ->addColumn('fun_des', 'string', ['limit' => 32, 'null' => false, 'default' => '', 'comment' => '功能描述'])
            ->addIndex('fun_url')
            ->setEngine('InnoDB')
            ->setCollation('utf8mb4_general_ci')
            ->setComment('菜单功能表')
            ->setData($this->getDefaultInsertData())
            ->create();
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
