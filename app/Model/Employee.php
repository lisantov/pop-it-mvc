<?php

namespace Model;

use Debug\DebugTools;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'inn',
        'snils',
        'account_number',
        'department_id',
    ];

    //Выборка сотрудника по первичному ключу
    public static function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    //Выборка сотрудника по первичному ключу
    public function getFullName()
    {
        return ($this->last_name.' '.$this->first_name.' '.($this->patronymic ?? ''));
    }
}