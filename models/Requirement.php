<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $table = 'requirements';
    protected $fillable = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
