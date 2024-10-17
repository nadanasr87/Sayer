<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCreditCard extends Model
{

    protected $table = 'credit_cards';

    protected $casts = [
        'user_id' => 'integer',
        'number' => 'string',
        'cvv' => 'string',
        'expired' => 'string',
        'cardHolder' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
