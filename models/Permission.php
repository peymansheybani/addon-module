<?php


namespace greenweb\addon\models;


use Illuminate\Database\Eloquent\Model;
use WHMCS\User\Admin;

/**
 * Class Permission
 * @package greenweb\addon\models
 *
 * @property json $permissions
 * @property int $role_id
 */
class Permission extends Model
{
    protected $table = 'green_permission';

    protected $fillable =[
        'permissions','role_id'
    ];

    protected $casts = [
      'permissions' => 'array'
    ];

    public static function hasPerm($perm, $user_id = null)
    {
        $user_id = $user_id ?: Admin::find($_SESSION['adminid'])->roleid;
        $permissions = self::where('role_id', $user_id)
            ->firstOrFail();

        return in_array($perm, $permissions->permissions);
    }

    public static function savePermission($request)
    {
        $permission = new static();
        $permission->role_id = $_POST['role_id'];
        $permission->permissions = $_POST['perms'];

        return $permission->save();
    }
}