<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Audience extends Model
{
    protected $table = 'audiences';
    protected $fillable = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
