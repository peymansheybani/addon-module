<?php


namespace greenweb\addon\models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tbladminroles';

    protected $fillable = [
        'name', 'widgets', 'reports'
    ];

    public static function hasFullAdminRole()
    {
        return self::join('tbladmins', 'tbladmins.roleid', '=', 'tbladminroles.id')
            ->where('tbladminroles.name', 'like', 'Full Administrator')
            ->where('tbladmins.id', '=', $_SESSION['adminid'])
            ->first();
    }

    public static function allNotFullAdmin()
    {
        return self::where('name', '<>', 'Full Administrator')->get();
    }
}