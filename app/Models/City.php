<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\ZoneScope;

/**
 * Class Zone
 *
 * @property int $id
 * @property string $name
 * @property mixed $regionId
 * @property int $cityId
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class City extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $table = 'city';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'regionId',
        'cityId',
    ];

}
