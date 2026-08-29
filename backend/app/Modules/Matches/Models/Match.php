<?php

namespace App\Modules\Matches\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Match extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'competition_id',
        'date',
        'venue',
        'status',
        'home_score',
        'away_score',
    ];
}
