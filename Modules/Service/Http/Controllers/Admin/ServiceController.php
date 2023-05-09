<?php

namespace Modules\Service\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;
use Modules\Service\Dto\ServiceData;
use Modules\Service\Http\Requests\Admin\CreateServiceRequest;
use Modules\Service\Models\ServicePeriod;
use Modules\Service\Repository\ServiceRepository;
use Inertia\Inertia;

class ServiceController extends Controller
{
    private ServiceRepository $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = $this->serviceRepository->root();

        return Inertia::render('Admin/Service/Index', ['services' => $services, 'title' => 'Управление сервисами']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicesList = $this->serviceRepository->root();
        $servicePeriods = ServicePeriod::get();

        return Inertia::render('Admin/Service/Create', [
            'title' => 'Добавление сервиса',
            'servicesList' => $servicesList,
            'servicePeriods' => $servicePeriods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceRequest $request)
    {
        $dto = new ServiceData($request->validated());
        $this->serviceRepository->create($dto->toArray());

        return redirect()->route('admin.service.index')->with('message', 'Сервис добавлен');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $servicesList = $this->serviceRepository->root();
        $servicePeriods = ServicePeriod::get();

        return Inertia::render('Admin/Service/Edit', [
            'title' => 'Редактирование сервиса',
            'service' => $this->serviceRepository->find($id),
            'servicesList' => $servicesList,
            'servicePeriods' => $servicePeriods,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateServiceRequest $request, string $id)
    {
        $dto = new ServiceData($request->validated());
        $this->serviceRepository->update($id, $dto->toArray());

        return redirect()->route('admin.service.index')->with('message', 'Сервис изменен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
