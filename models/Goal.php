<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goals';
    protected $fillable = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
