<?php
namespace app\model;

use think\Model;

class Verification extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    
    // 状态常量
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    
    // 获取状态文本
    public function getStatusTextAttr()
    {
        $status = [
            self::STATUS_PENDING => '待审核',
            self::STATUS_APPROVED => '已通过',
            self::STATUS_REJECTED => '已拒绝',
        ];
        return $status[$this->status] ?? '未知';
    }
    
    // 关联网站
    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    
    // 关联用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}