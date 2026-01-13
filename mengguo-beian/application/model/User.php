<?php
namespace app\model;

use think\Model;

class User extends Model
{
    protected $pk = 'id';
    protected $autoWriteTimestamp = true;
    
    protected $hidden = ['password'];
    
    // 验证用户密码
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}