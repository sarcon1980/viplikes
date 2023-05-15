<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Service\Builders\ServiceBuilder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        die('need modifing');

        ServiceBuilder::builder()
            ->setName('Facebook')
            ->setActive(true)
            ->setRoot()
            ->createIfNotExists();


        ServiceBuilder::builder()
            ->setName('Instagram')
            ->setActive(true)
            ->setRoot()
            ->createIfNotExists();


        ServiceBuilder::builder()
            ->setName('Facebook Likes')
            ->setProductType()
            ->setActive(true)
            ->setParent('facebook')
            ->setItems([
                    ['name' => 100, 'position' => '1',
                        'price' => 50, 'count' => 100, 'price_for_sale' => 30, 'discount' => 5,
                        'type' => 'high-quality'
                    ],
                    ['name' => 200, 'position' => '2',
                        'price' => 70, 'count' => 200, 'price_for_sale' => 50, 'discount' => 7,
                        'type' => 'real'
                    ]
                ]
            )
            ->setOptions([
                'title' => 'like',
                'accounts' => json_encode([
                    ['alias' => 'email', 'title' => 'E-mail'],
                    ['alias' => 'username', 'title' => '@username'],
                ]),
                'types' => json_encode([
                    ['alias' => 'high-quality', 'title' => 'High-Quality Likes'],
                    ['alias' => 'real', 'title' => 'Buy Real Likes']])
            ])
            ->createIfNotExists();


        ServiceBuilder::builder()
            ->setName('Facebook Packages')
            ->setPackageType()
            ->setActive(true)
            ->setParent('facebook')
            ->setItems([
                    ['name' => '<p>20 Likes </p> <p>50 Followers</p> <p>100 Comments </p>', 'position' => '1',
                        'price' => 50, 'price_for_sale' => 30, 'discount' => 5,
                        'type' => 'day'
                    ],
                    ['name' => '<p>40 Likes </p> <p>150 Followers</p> <p>200 Comments </p>', 'position' => '1',
                        'price' => 50, 'price_for_sale' => 30, 'discount' => 5,
                        'type' => 'day'
                    ],

                    ['name' => '<p>30 Likes </p> <p>70 Followers</p> <p>100 Comments </p>', 'position' => '2',
                        'price' => 70, 'price_for_sale' => 50, 'discount' => 7,
                        'type' => 'week'
                    ]
                ]
            )
            ->setOptions([
                'title' => json_encode(['likes', 'followers', 'comments']),
                'accounts' => json_encode([
                    ['alias' => 'email', 'title' => 'E-mail'],
                    ['alias' => 'username', 'title' => '@username'],
                ]),
                'types' => json_encode([
                    ['alias' => 'day', 'title' => 'Daily'],
                    ['alias' => 'week', 'title' => 'Weekly'],
                    ['alias' => 'mount', 'title' => 'Monthly']
                ])
            ])
            ->createIfNotExists();
    }
}
