<?php

namespace Modules\Service\Http\Controllers\Admin;

use Modules\Service\Dto\ServiceItemData;
use Modules\Service\Http\Requests\Admin\CreateServiceItemRequest;
use Modules\Service\Http\Requests\Admin\PositionServiceItemRequest;
use Modules\Service\Models\Service;
use Modules\Service\Repository\ServiceItemRepository;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Service\Repository\ServiceRepository;

class ServiceItemController extends Controller
{
    private ServiceItemRepository $serviceItemRepository;

    private ServiceRepository $serviceRepository;

    public function __construct(ServiceItemRepository $serviceItemRepository, ServiceRepository $serviceRepository)
    {
        $this->serviceItemRepository = $serviceItemRepository;

        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Service $service)
    {
        $items = $this->serviceItemRepository->searchItems($service->id)->get();
    //dd($items);

        // dd($this->serviceItemRepository->getItemsParentWithoutPackage($service->parent_id) );

        $type = ucfirst($service->type);
        return Inertia::render("Admin/ServiceItem/Index{$type}", [
            'service' => $service,
            'items' => $items,
            'title' => $service->name . ': управление продуктами',
            'itemsForPackage' => $service->isPackageType() ?
                $this->serviceItemRepository->getItemsParentWithoutPackage($service->parent_id)
                : null
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

        return redirect()->route('admin.service-items.index', ['service' => $dto->service_id]);
        //return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateServiceItemRequest $request, string $id)
    {
        //dd($request->post());
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
