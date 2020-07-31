<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['value'];

    public function rateable(){
        return $this->morphTo();
    }
}
