<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = [];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
