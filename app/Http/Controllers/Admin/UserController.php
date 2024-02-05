<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdatePasswordRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->prefix = 'admin.users';
        $this->pageName = 'Users';

        $this->middleware('permission:Users', ['only' => 'index']);
        $this->middleware('permission:Users Show', ['only' => 'show']);
        $this->middleware('permission:Users Store', ['only' => 'store']);
        $this->middleware('permission:Users Update', ['only' => ['update', 'updatePassword']]);
        $this->middleware('permission:Users Destroy', ['only' => 'destroy']);
    }

    private function getPageData()
    {
        $data = [
            'pageTitle' => $this->pageName,
            'pageDescription' => '',
            'breadcrumbItems' => [
                ['name' => 'Users', 'url' => route('users.index')],
            ],
            'roles' => Role::where('name', '!=', 'superadmin')->get(),
            'users' => User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'superadmin');
            })->get(),
        ];

        return $data;
    }

    private function getAjaxResponse()
    {
        $users = User::orderBy('id', 'desc')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'superadmin');
            })
            ->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('no', function ($item) {
                static $no = 1;
                return $no++ . '.';
            })
            ->addColumn('roles', function ($item) {
                $roles = $item->getRoleNames()->first();
                $color = ($roles == 'admin') ? 'primary' : (($roles == 'user') ? 'success' : 'danger');
                $icon = ($roles == 'admin') ? 'laptop' : 'account-outline';

                return '<span class="text-truncate d-flex align-items-center">
                            <i class="mdi mdi-' . $icon . ' mdi-20px text-' . $color . ' me-2"></i>
                            ' . $roles . '
                        </span>';
            })
            ->addColumn('action', function ($item) {
                $editPasswordButton = '<button type="button" class="btn btn-label-warning waves-effect" title="Edit Password" data-bs-toggle="modal" data-bs-target="#editUserPasswordModal" data-id="' . $item->uuid . '">
                                    <i class="mdi mdi-lock-outline"></i>
                                </button>';
                $editButton = '<button type="button" class="btn btn-label-primary waves-effect" title="Edit" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="' . $item->uuid . '">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </button>';
                $deleteButton = '<button type="button" class="btn btn-label-danger waves-effect" title="Delete" id="deleteUserButton" data-id="' . $item->uuid . '">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </button>';

                return '<div class="text-center">' . $editPasswordButton . ' ' . $editButton . ' ' . $deleteButton . '</div>';
            })
            ->rawColumns(['action', 'roles'])
            ->make(true);
    }

    public function index()
    {
        try {
            $data = $this->getPageData();

            if (request()->ajax()) {
                return $this->getAjaxResponse();
            }

            return view($this->prefix . '.index', $data);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $request->validated();

            // Check if the user with the given email exists
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email already exists.',
                    'errors' => [
                        'email' => ['Email already exists.'],
                    ],
                ], 422);
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'uuid' => Str::uuid(),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role
            $user->assignRole($request->roles);

            return response()->json([
                'status' => 'success',
                'message' => 'Create New User successful.',
                'data' => [
                    'user' => $user,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function show($uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
            $user->role = $user->getRoleNames()->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Get User successful.',
                'data' => [
                    'user' => $user,
                ],
            ], 200);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, $uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
            $request->validated();

            // Update the user
            $user->update([
                'name' => $request->name,
            ]);

            // Assign role
            $user->syncRoles($request->roles);

            return response()->json([
                'status' => 'success',
                'message' => 'Update User successful.',
                'data' => [
                    'user' => $user,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function updatePassword(UserUpdatePasswordRequest $request, $uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
            $request->validated();
            // Update the user
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Update Password User successful.',
                'data' => [
                    'user' => $user,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function destroy($uuid)
    {
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Delete User successful.',
                'data' => [
                    'user' => $user,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

}
