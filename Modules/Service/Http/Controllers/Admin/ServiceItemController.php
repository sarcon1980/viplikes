<?php

namespace Modules\Service\Http\Controllers\Admin;

use Modules\Service\Dto\ServiceItemData;
use Modules\Service\Http\Requests\Admin\CreateServiceItemRequest;
use Modules\Service\Http\Requests\Admin\PositionServiceItemRequest;
use Modules\Service\Models\Service;
use Modules\Service\Repository\ServiceItemRepository;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;

class ServiceItemController extends Controller
{
    private ServiceItemRepository $serviceItemRepository;

    public function __construct(ServiceItemRepository $serviceItemRepository)
    {
        $this->serviceItemRepository = $serviceItemRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Service $service)
    {
        $items = $this->serviceItemRepository->searchItems($service->id)->get();

        $type = ucfirst($service->type);
        return Inertia::render("Admin/ServiceItem/Index{$type}", [
            'service' => $service,
            'items' => $items,
            'title' => $service->name . ': управление продуктами'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceItemRequest $request)
    {
       // dd($request->post());

        $dto = new ServiceItemData($request->validated());
        $this->serviceItemRepository->create($dto->toArray());

        return redirect()->route('admin.service-items.index',['service'=> $dto->service_id]);
        //return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateServiceItemRequest $request, string $id)
    {
        $dto = new ServiceItemData($request->validated());
        $this->serviceItemRepository->update($id, $dto->toArray());

        return redirect()->back();
        //route('admin.service-items.index',['service'=> $dto->service_id]); // Так не сохраняются гет параметры
    }

    public function position(PositionServiceItemRequest $request)
    {
        $this->serviceItemRepository->updatePosition($request->data);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
