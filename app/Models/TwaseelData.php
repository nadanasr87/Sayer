<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwaseelData extends Model
{
    use HasFactory;
    protected $table = 'tawseel_records';

    protected $casts = [
        'type' => 'string',
        'reference' => 'string',
        'reference_table' => 'string',
        'data' => 'json',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'type',
        'reference',
        'reference_table' ,
        'data' ,
        'status' ,
        'created_at',
        'updated_at',
    ];
}
