<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    //protected $with = ['serviceItem'];
    protected $casts = [
        'order_id' => 'int',
        'service_item_id' => 'int',
        'created_at' => 'datetime',
        'update_at' => 'datetime',
        'count' => 'int',
        'price_for_sale' => 'float',
        'price' => 'float',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['service_item_id', 'order_id', 'count', 'price', 'price_for_sale'];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(resolve(Order::class), 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function serviceItem(): BelongsTo
    {
        return $this->belongsTo(resolve(ServiceItem::class), 'service_item_id')->with('service');
    }
}
