<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = ['short_url', 'original_url'];   //TO-DO it should only include short_url.
    
    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
