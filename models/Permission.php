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
        $user = $user_id ? Admin::find($user_id): Admin::find($_SESSION['adminid']);

        $permission = self::getPermission($user->roleid);

        return in_array($perm, $permission->permissions);
    }

    public static function savePermission($request)
    {
        $permission = null;
        if (!$permission = self::getPermission($request['role_id'])){
            $permission = new static();
        }

        if ($request['perms'] == null && isset($permission->id)) {
            return $permission->delete();
        }

        $permission->role_id = $request['role_id'];
        $permission->permissions = $request['perms'];

        return $permission->save();
    }

    private static function getPermission($role_id)
    {
        return self::where('role_id', $role_id)
            ->first();
    }
}