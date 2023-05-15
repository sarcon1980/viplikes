<?php

namespace Modules\User\Http\Controllers\Admin;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Models\User;
use Modules\User\Repository\RoleRepository;
use Modules\User\Repository\UserRepository;

class UserController extends Controller
{
    private UserRepository $userRepository;

    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $users = $this->userRepository->search();

        return Inertia::render('Admin/Users/Index', [
            'title' => 'Управление пользователями',
            'users' => $users,
            'roles' => $this->roleRepository->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $userData = $request->getDto();
        $this->userRepository->create($userData->toArray());

        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\User\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        $userData = $request->getDto();
        $this->userRepository->update($user->id, $userData->toArray());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Modules\User\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->userRepository->destroy($user->id);
        return back();
    }

}
