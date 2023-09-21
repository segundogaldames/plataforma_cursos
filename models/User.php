<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [];

    //uno a uno
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    //uno a muchos

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function courseUser()
    {
        return $this->hasMany(CourseUser::class);    
    }

    public function lessonUser()
    {
        return $this->belongsTo(LessonUser::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
