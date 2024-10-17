<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class StcPayment extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $table = 'stc_delivery_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


}
