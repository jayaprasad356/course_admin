<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
