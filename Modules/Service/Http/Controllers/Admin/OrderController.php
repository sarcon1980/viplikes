<?php

namespace Modules\Service\Http\Controllers\Admin;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Service\Dto\adminOrderData;
use Modules\Service\Http\Requests\Admin\UpdateOrderRequest;
use Modules\Service\Models\Order;
use Modules\Service\Repository\OrderRepository;

class OrderController extends Controller
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = $this->orderRepository->search();

        return Inertia::render('Admin/Order/Index',
            [
                'title' => 'Управление заказами',
                'orders' => $orders,
                'statusList'=>Order::getStatusList()

            ]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {

        $dto = new adminOrderData($request->validated());
        $this->orderRepository->update($order->id, $dto->toArray());

        return back();
    }
}
