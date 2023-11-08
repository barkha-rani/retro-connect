<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    public function cards()
    {
        return $this->hasMany(Card::class, 'column_id', 'id')->orderBy('created_at');
    }

    public function board()
    {
        return $this->belongsTo(Card::class, 'board_id', 'id');
    }
}
