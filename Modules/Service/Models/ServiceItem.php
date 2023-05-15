<?php

namespace Modules\Service\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $service_id
 * @property int $position
 * @property int $count
 * @property float $price
 * @property float $price_for_sale
 * @property float $discount
 * @property bool $is_active
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime|null $deleted_at
 *
 */
class ServiceItem extends Model
{
    use Userstamps, SoftDeletes, HasFactory;

    const IS_ACTIVE = true;

    const COUNT_PACKAGE_ITEMS = 3;

    protected $appends = ['package_items'];
   // protected $with= ['service'];

    protected $fillable = [
        'is_active',
        'name',
        'service_id',
        'position',
        'price',
        'count',
        'price_for_sale',
        'discount',
        'type'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'is_active' => 'int',
        'update_at' => 'datetime',
        'position' => 'int',
        'service_id' => 'int',
        'price' => 'float',
        'count' => 'int',
        'price_for_sale' => 'float',
        'discount' => 'float',
        'name' => AsCollection::class
    ];


    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', self::IS_ACTIVE);
    }

    public function getPackageItemsAttribute()
    {
        return $this->itemsPackage()->get()->map(function ($item) {
            return [
                "id" => $item['id'],
                "name" => $item['count'] . ' ' . $item->service->options->title . ' '. $item['type'],
                "type" => $item['type']
            ];
        });
    }

    //Для состава пакета
    public function itemsPackage(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'service_package_items', 'service_item_id', 'item_id')
            ->withTimestamps();
    }

//Для состава пакета
    public function package(): BelongsTo
    {
        return $this->belongsTo(self::class, 'service_package_items', 'id', 'id');
    }
}
