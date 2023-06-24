<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }
}
