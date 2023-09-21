<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $table = 'descriptions';
    protected $fillable = [];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
