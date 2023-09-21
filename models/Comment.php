<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = '';
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
