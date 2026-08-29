<?php

namespace App\Modules\Trainings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'date',
        'location',
        'type',
        'notes',
    ];
}
