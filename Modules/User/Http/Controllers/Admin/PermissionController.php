<?php

namespace Modules\User\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;
use Modules\User\Dto\PermissionData;
use Modules\User\Http\Requests\CreatePermissionRequest;
use Modules\User\Http\Requests\UpdatePermissionRequest;
use Modules\User\Models\Permission;
use Inertia\Inertia;
use Modules\User\Repository\PermissionRepository;

class PermissionController extends Controller
{
    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = $this->permissionRepository->search();

        return Inertia::render('Admin/Users/Permissions/Index', [
            'permissions' => $permissions,
            'title' => 'Права'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePermissionRequest $request)
    {

        $data = new PermissionData($request->validated());

        $this->permissionRepository->create($data->toArray());

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\User\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {

        $data = new PermissionData($request->validated());

        $this->permissionRepository->update($permission->id, $data->toArray());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Modules\User\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back();
    }
}
