<?php

namespace App;

/**
 * Trait Rateable to enable polymorphic ratings on a model
 * @package App
 */
trait Rateable
{
    public function ratings(){
        return $this->morphMany(Rating::class, 'rateable');
    }
}
