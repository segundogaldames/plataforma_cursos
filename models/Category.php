<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
