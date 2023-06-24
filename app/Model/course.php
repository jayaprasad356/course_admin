<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    public function cayegories()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }
}

?>
