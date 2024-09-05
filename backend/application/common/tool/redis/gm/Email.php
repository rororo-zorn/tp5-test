<?php

namespace app\common\tool\redis\gm;

use app\common\tool\redis\Expire;

class Email extends Expire
{
    /**
     * sub key
     * @var string
     */
    protected $subKey = 'email';

    /**
     * redis key
     * @var string
     */
    protected $key;

    /**
     * @var \app\common\model\backend\gm\Email
     */
    protected $model;

    /**
     * Email constructor.
     * @param \app\common\model\backend\gm\Email $model
     */
    public function __construct($model)
    {
        parent::__construct();

        $this->model = $model;
        $this->key = sprintf('%s:%d', $this->subKey, $model->getId());
    }

    /**
     * 添加
     * @return bool
     */
    public function add()
    {
        return $this->handler->setex($this->key, $this->model->getExpiredTime(), 1);
    }

    /**
     * 删除
     * @return int
     */
    public function delete()
    {
        return $this->handler->del($this->key);
    }
}