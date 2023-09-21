<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table = 'platforms';
    protected $fillable = [];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
