<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'created_at' => 'datetime',
        'update_at' => 'datetime',
        'position' => 'int',
        'service_id' => 'int',
        'price' => 'float',
        'count' => 'int',
        'price_for_sale' =>'float',
        'discount' => 'float'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

}
