<?php

namespace Model;

use Debug\DebugTools;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePost extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'employee_posts';
    protected $fillable = [
        'employee_id',
        'post_id',
    ];

    public static function getEmployeePosts($id)
    {
        $employeePosts = self::where('employee_id', $id)->get(); // Collection of models

        $postIds = $employeePosts->pluck('post_id');
        $posts = Post::whereIn('id', $postIds)->get();

        return $posts;
    }
}