<?php

namespace Modules\User\Http\Controllers\Admin;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Models\User;
use Modules\User\Repository\UserRepository;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->search();

        return Inertia::render('Admin/Users/Index', [
            'title' => 'Управление пользователями',
            'users' => $users,
            //   'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $this->validate($request, [
                'name' => ['required', 'max:50'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => 0,
                'password' => Hash::make('password')
            ]);
            $role = Role::where('id', 5)->first();
            $user->syncRoles($role);
            return back();
        }
        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\User\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $this->validate($request, [
                'name' => ['required', 'max:50'],
                'email' => ['required', 'string', 'email', 'max:50'],
            ]);
            if ($request->roles[0] === null) {
                return back()->withErrors(['roles' => 'The role field is required']);
            }
            if ($request->roles[0]['id'] != 5) {
                $adminRole = Role::where('id', $request->roles[0]['id'])->first();
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'is_admin' => 1,
                ]);
                $user->syncRoles($adminRole);
                return back();
            } else {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
            }
            return back();
        }
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
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $user->delete();
            return back();
        }
        return back();
    }

}
