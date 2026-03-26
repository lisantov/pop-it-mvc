<?php

namespace Model;

use Debug\DebugTools;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    //Выборка роли по первичному ключу
    public static function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }
}