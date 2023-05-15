<?php

namespace Modules\Service\Builders;

use Illuminate\Support\Str;
use Modules\Service\Models\Order;
use Modules\Service\Models\OrderItem;
use Modules\Service\Models\ServiceItem;
use Modules\User\Models\User;

class OrderBuilder
{
    public int $user_id;

    public string $payment_status;

    public string $payment_type;

    public string $payment_period = '';

    public string $status = 'new';

    public $payed_at = null;

    public $products = [];

    public static function builder(): self
    {
        return resolve(self::class);
    }

    public function setPaymentTypeOrder()
    {
        $this->payment_type = 'order';
        return $this;
    }

    public function setPaymentTypeSubscribe()
    {
        $this->payment_type = 'subscribe';
        return $this;
    }

    public function setUser(User $user)
    {
        $this->user_id = $user->id;
        return $this;
    }

    public function setStatus(string $status)
    {
        if ($this->payment_type == 'subscribe') {
            $this->status = 'completed';

        }
        return $this;
    }

    public function setPaymentStatus($status)
    {
        $this->payment_status = $status;
        if ($status == 'payed') {
            $this->payed_at = \Carbon\Carbon::now()->subDays(rand(0, 90));
        }
        return $this;
    }

    public function setPaymentType($type)
    {
        $this->payment_type = $type;
        return $this;
    }


    public function setProducts($product)
    {
        $this->products[] = $product;
        return $this;
    }

    public function setPaymentPeriod($period)
    {
        if ($this->payment_type == 'subscribe') {
            $this->payment_period = $period;
        }

        return $this;
    }


    public function create()
    {

//        if ($this->items == []) {
//            return throw new \Exception('Необходимо заполнить items');
//        }

        $model = new Order();
        $model->user_id = $this->user_id;
      //  $model->status = $this->status;

        $model->payed_at = $this->payed_at;
        $model->payment_type = $this->payment_type;
        $model->payment_status = $this->payment_status;
        $model->payment_period = $this->payment_period;

         $model->save();

         $model->setStatus($this->status, 'Reason: ' . Str::random(17) );


        if ($this->payment_type == 'order') {
            $product = ServiceItem::query()->inRandomOrder()->whereHas('service', function ($q) {
                $q->where('type', 'product');
            })->first();

            $item = new OrderItem();
            $item->order_id = $model->id;
            $item->service_item_id = $product->id;
            $item->price = $product->price;
            $item->price_for_sale = $product->price_for_sale;
            $item->count = $product->count;
             $item->save();


        } elseif ($this->payment_type == 'subscribe') {
            $products = ServiceItem::query()->inRandomOrder()->whereHas('service', function ($q) {
                $q->where('type', 'package');
            })->first()->itemsPackage;

            //dd($this->items);
            foreach ($products as $k => $product) {
                //dd($product->toArray());
                $item = new OrderItem();
                $item->order_id = $model->id;
                $item->service_item_id = $product->id;
                $item->price = $product->price;
                $item->price_for_sale = $product->price_for_sale;
                $item->count = $product->count;
                $item->save();
            }
        }
    }
}
