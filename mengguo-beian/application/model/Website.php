<?php
namespace app\model;

use think\Model;

class Website extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    
    protected $type = [
        'status' => 'integer',
        'can_verify' => 'boolean',
    ];
    
    // 状态常量
    const STATUS_NORMAL = 1;
    const STATUS_SUSPENDED = 2;
    const STATUS_REJECTED = 3;
    
    // 获取状态文本
    public function getStatusTextAttr()
    {
        $status = [
            self::STATUS_NORMAL => '正常',
            self::STATUS_SUSPENDED => '已暂停',
            self::STATUS_REJECTED => '已拒绝',
        ];
        return $status[$this->status] ?? '未知';
    }
}