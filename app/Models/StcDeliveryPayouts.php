<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class StcDeliveryPayouts extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $table = 'stc_delivery_payouts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'mobile',
    //     'ItemReference',
    //     'Amount',
    // ];   

    public function StcBulkPayouts()
    {
        return $this->belongsTo(StcBulkPayouts::class,'bulk_payment_id');
    }
    public function DeliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class,'bulk_payment_id');
    }
}
