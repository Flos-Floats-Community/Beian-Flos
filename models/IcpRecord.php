<?php
// 备案记录模型类
require_once __DIR__ . '/Database.php';

class IcpRecord {
    // 提交备案
    public static function add($data) {
        $stmt = Database::query(
            'INSERT INTO icp_records (user_id, site_name, domain, homepage, site_info, other_icp_number, icp_community, owner, subject, status, create_time, update_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
            [
                $data['user_id'],
                $data['site_name'],
                $data['domain'],
                $data['homepage'],
                $data['site_info'],
                $data['other_icp_number'] ?? '',
                $data['icp_community'] ?? '',
                $data['owner'],
                $data['subject']
            ]
        );
        
        return $stmt->rowCount() > 0;
    }
    
    // 获取所有已通过的备案记录
    public static function getAllApproved() {
        $stmt = Database::query('SELECT * FROM icp_records WHERE status = 1 ORDER BY update_time DESC');
        return $stmt->fetchAll();
    }
    
    // 获取用户的备案记录
    public static function getUserRecords($user_id) {
        $stmt = Database::query('SELECT * FROM icp_records WHERE user_id = ? ORDER BY update_time DESC', [$user_id]);
        return $stmt->fetchAll();
    }
    
    // 获取待审核的备案记录
    public static function getPendingRecords() {
        $stmt = Database::query('SELECT * FROM icp_records WHERE status = 0 ORDER BY create_time ASC');
        return $stmt->fetchAll();
    }
    
    // 获取备案记录详情
    public static function getById($id) {
        $stmt = Database::query('SELECT * FROM icp_records WHERE id = ?', [$id]);
        return $stmt->fetch();
    }
    
    // 审核通过备案
    public static function approve($id) {
        // 生成备案号（浮ICP + 7位随机数）
        $randomNum = str_pad(mt_rand(1000000, 9999999), 6, '0', STR_PAD_LEFT);
        $icp_number = '浮ICP' . $randomNum;
        
        $stmt = Database::query(
            'UPDATE icp_records SET status = 1, icp_number = ?, subject_verified = 1, update_time = CURRENT_TIMESTAMP WHERE id = ?',
            [$icp_number, $id]
        );
        
        return $stmt->rowCount() > 0;
    }
    
    // 拒绝备案
    public static function reject($id) {
        $stmt = Database::query(
            'UPDATE icp_records SET status = 2, update_time = CURRENT_TIMESTAMP WHERE id = ?',
            [$id]
        );
        
        return $stmt->rowCount() > 0;
    }
}
