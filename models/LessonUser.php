<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class LessonUser extends Model
{
    protected $table = 'lesson_user';
    protected $fillable = [];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
