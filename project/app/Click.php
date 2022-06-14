<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $fillable = ['browser', 'platform'];
    
    public function url()
    {
        return $this->belongsTo(Click::class);
    }
}
