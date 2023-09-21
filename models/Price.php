<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $fillable = [];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
