<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'salary',
        'salary_bonus',
        'salary_penalty',
    ];

    //Выборка должности по первичному ключу
    public static function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }
}