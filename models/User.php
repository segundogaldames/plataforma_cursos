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

    //muchos a muchos

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function courseUser()
    {
        return $this->hasMany(CourseUser::class);    
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
