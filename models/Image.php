<?php
// example of using model with eloquent
namespace models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = [];
}
