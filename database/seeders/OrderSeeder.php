<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Modules\Service\Builders\OrderBuilder;
use Modules\Service\Models\Order;
use Modules\Service\Models\ServiceItem;
use Modules\User\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {

            $paymentTypes = ['subscribe', 'order'];

            $statuses = [
                'new',
                'in_progress',
                'error',
                'completed',
                'renewal',
                'rejected',
            ];

            $periods = ['day', 'week', 'month'];
            $paymentStatus = ['payed', 'no_payed'];

            OrderBuilder::builder()
                //->setPaymentTypeOrder()
               // ->setPaymentTypeSubscribe()
                ->setPaymentType($paymentTypes[array_rand($paymentTypes)])
                ->setUser(User::inRandomOrder()->first())
                ->setStatus($statuses[array_rand($statuses)])
                ->setPaymentStatus($paymentStatus[array_rand($paymentStatus)])
                ->setPaymentPeriod($periods[array_rand($periods)])
                ->create();
        }
    }

}
