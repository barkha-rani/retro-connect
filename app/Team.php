<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function members()
    {
        return $this->hasMany(User::class, 'team_id', 'id');
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'team_id', 'id');
    }
}
