<?php

namespace Modules\Service\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;
use Spatie\ModelStatus\HasStatuses;
use Wildside\Userstamps\Userstamps;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $payment_type
 * @property string $payment_status
 * @property string $status
 * @property string $payment_period
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $payed_at
 *
 * @property-read Collection|OrderItem[] $items
 * @property-read User $user
 * @property-read float $total_price
 * @property-read float $total_price_for_sale
 *
 */
class Order extends Model
{
    use Userstamps, SoftDeletes, HasFactory, HasStatuses;

    protected $with = ['user', 'items', 'items.serviceItem', 'statuses'];

    protected $fillable = [
        'user_id',
        'payment_status',
        'payment_type',
        'payment_period',
        'status',
        'payed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime',
        'payed_at' => 'datetime:d-m-Y h:i',
        'user_id' => 'int',
    ];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(resolve(User::class), 'user_id')->withTrashed();
    }

    public function addItems($items)
    {
        /** @var ServiceItem $item */
        foreach ($items as $item) {
            $this->items()->create([
                'price' => $item->price,
                'price_for_sale' => $item->price_for_sale,
                'quantity' => $item->count,
                'service_item_id' => $item->id,
            ])->save();
        }
    }

//    public function setPayed()
//    {
//        $this->payment_status = PaymentStatusEnum::STATUS_PAYED;
//        $this->payed_at = Carbon::now();
//
//        if(! $this->save()) {
//            throw new Exception('Order save error');
//        }
//    }

//    public function isPayed(): bool
//    {
//        return PaymentStatusEnum::STATUS_PAYED === $this->payment_status;
//    }

    public function scopePayed(Builder $query)
    {
        return $query->whereNotNull('orders.payed_at');
    }

    public static function getStatusList()
    {

        return [
            'new',
            'in_progress',
            'error',
            'completed',
            'renewal',
            'rejected',
        ];
    }
}
