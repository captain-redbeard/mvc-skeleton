<?php
/**
 * @author captain-redbeard
 * @since 20/01/17
 */
namespace Redbeard\Models;

use Redbeard\Core\Database;

class Role
{
    public $permissions = null;
    
    public static function getRolePerms($role_id)
    {
        $role = new Role();
        $query_perms = Database::select(
            "SELECT t2.perm_desc
            
            FROM role_perm as t1
            JOIN permissions as t2 ON t1.perm_id = t2.perm_id
            
            WHERE t1.role_id = ?;",
            [$role_id]
        );
        
        foreach ($query_perms as $perm) {
            $role->permissions[$perm['perm_desc']] = true;
        }
        
        return $role;
    }
    
    public function hasPermission($permission)
    {
        return isset($this->permissions[$permission]);
    }
}
