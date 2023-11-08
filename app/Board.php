<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    public function columns()
    {
        return $this->hasMany(Column::class, 'board_id', 'id')->orderBy('order');
    }
}
