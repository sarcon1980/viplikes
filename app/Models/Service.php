<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $type
 * @property int|null $parent_id
 * @property int $position
 * @property string $url
 * @property bool $is_active
 * @property bool $is_bestseller
 * @property bool $is_sale
 * @property bool $is_popular
 * @property bool $is_recommendation
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime|null $deleted_at
 *
 */
class Service extends Model
{
    use HasFactory;

    const IS_ACTIVE = true;
    const TYPE_PACKAGE = 'package';
    const TYPE_PRODUCT = 'product';

    protected $fillable = [
        'name',
        'parent_id',
        'position',
        'url',
        'alias',
        'type',
        'is_active',
        'is_bestseller',
        'is_sale',
        'is_popular',
        'is_recommendation'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'update_at' => 'datetime',
        'position' => 'int',
        'parent_id' => 'int',
        'is_active' => 'bool',
        'is_bestseller' => 'bool',
        'is_sale' => 'bool',
        'is_popular' => 'bool',
        'is_recommendation' => 'bool'
    ];

    public function items(): hasMany
    {
        return $this->hasMany(ServiceItem::class, 'service_id');
    }

    public function options(): hasMany
    {
        return $this->hasMany(ServiceOption::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', self::IS_ACTIVE);
    }

    public function getNeedAccounts()
    {
        $accounts = $this->options()->pluck('accounts');
        return  json_decode($accounts[0]);
    }

    public function getTypes()
    {
        $type = $this->options()->pluck('types');
        return  json_decode($type[0]);
    }
}
