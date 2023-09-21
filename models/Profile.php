<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);    
    }
}
