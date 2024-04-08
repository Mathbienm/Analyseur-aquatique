<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Mesure extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'bassin_id',
        'temperature',
        'ph',
        'created_at',
        'updated_at',
    ];

    protected $guarded = ['id'];
}
