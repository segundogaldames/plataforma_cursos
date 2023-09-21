<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $table = 'reactions';
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
