<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'card_id', 'user_id');
    }

    public function column(){
        return $this->belongsTo(Column::class, 'column_id', 'id');
    }
}
