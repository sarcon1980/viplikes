<?php

namespace Modules\User\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;
use Modules\User\Dto\RoleData;
use Modules\User\Http\Requests\CreateRoleRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Inertia\Inertia;
use Modules\User\Repository\PermissionRepository;
use Modules\User\Repository\RoleRepository;

class RoleController extends Controller
{
    private RoleRepository $roleRepository;

    private PermissionRepository $permissionRepository;

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        return Inertia::render('Admin/Users/Roles/Index', [
            'roles' => $this->roleRepository->search(),
            'permissions' => $this->permissionRepository->all(),
            'title' => "Роли"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request)
    {
        $data = new RoleData($request->validated());
        $this->roleRepository->create($data->toArray());

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\User\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {

        $this->validate($request, [
            'name' => ['required', 'max:25'],
            'permissions' => 'required'
        ]);
        if ($request->has('permissions')) {
            $role->givePermissionTo(collect($request->permissions)->pluck('id')->toArray());
        }
        $role->syncPermissions(collect($request->permissions)->pluck('id')->toArray());
        $role->update(['name' => $request->name]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Modules\User\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return back();

    }
}
