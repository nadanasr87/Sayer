<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class StcBulkPayouts extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $table = 'bulk_stc_payouts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'regionId',
    //     'cityId',
    // ];   
    public function payouts()
    {
        return $this->hasMany(StcDeliveryPayouts::class,'bulk_payment_id');
    }
}
