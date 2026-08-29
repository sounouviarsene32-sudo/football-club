<?php

namespace App\Modules\Competitions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'season',
        'start_date',
        'end_date',
        'type',
    ];
}
