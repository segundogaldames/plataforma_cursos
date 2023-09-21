<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';
    protected $fillable = [];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    //uno a muchos

    public function descriptions()
    {
        return $this->hasMany(Description::class);
    }

    public function lessonUser()
    {
        return $this->belongsTo(LessonUser::class);
    }
}
