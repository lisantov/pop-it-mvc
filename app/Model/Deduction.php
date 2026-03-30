<?php

namespace Model;

use Debug\DebugTools;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'deductions';
    protected $fillable = [
        'employee_id',
        'name',
        'amount',
        'month_left',
    ];

    public static function findIdentity(int $id)
    {
        return self::where('id', $id)->first();
    }

    public static function getEmployeeDeductions(int $employee_id)
    {
        return self::where('employee_id', $employee_id)->get();
    }
}