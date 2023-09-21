<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    protected $table = 'course_user';
    protected $fillable = [];

    public function course()
    {
        return $this->belongsTo(Course::class);    
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
