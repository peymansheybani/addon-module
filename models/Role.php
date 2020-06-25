<?php


namespace greenweb\addon\models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbladminroles';

    protected $fillable = [
        'name', 'widgets', 'reports'
    ];

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

    public static function getRoleCurrentUser()
    {
        return self::join('tbladmins', 'tbladmins.roleid', '=', 'tbladminroles.id')
            ->where('tbladminroles.name', 'like', 'Full Administrator')
            ->first();
    }
}