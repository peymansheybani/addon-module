<?php


namespace greenweb\addon\models;


use Illuminate\Database\Eloquent\Model;
use WHMCS\User\Admin;

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

    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }

    public function resolveRouteBinding($value, $field = null)
    {
        // TODO: Implement resolveRouteBinding() method.
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}