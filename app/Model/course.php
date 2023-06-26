<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course'; // Specify the table name if it's different from the model name

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}
