<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    public function categories()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }
}
