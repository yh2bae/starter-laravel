<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionStoreRequest;
use App\Http\Requests\Admin\PermissionUpdateRequest;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->prefix = 'admin.permissions';
        $this->pageName = 'Permissions';

        $this->middleware('permission:Permission', ['only' => 'index']);
        $this->middleware('permission:Permission Show', ['only' => 'show']);
        $this->middleware('permission:Permission Store', ['only' => 'store']);
        $this->middleware('permission:Permission Update', ['only' => 'update']);
        $this->middleware('permission:Permission Destroy', ['only' => 'destroy']);
    }

    private function getPageData()
    {
        $data = [
            'pageTitle' => $this->pageName,
            'pageDescription' => '',
            'breadcrumbItems' => [
                ['name' => 'Permission', 'url' => route('permissions.index')],
            ],
        ];

        return $data;
    }

    private function getAjaxResponse()
    {
        $permissions = Permission::orderBy('id', 'desc')->get()->map(function ($permission) {
            $roleNames = $permission->roles->pluck('name')->toArray();
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'module_name' => $permission->module_name,
                'guard_name' => $permission->guard_name,
                'default_permission' => $permission->default_permission,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
                'roles' => $roleNames,
            ];
        });

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('no', function ($item) {
                static $no = 1;
                return $no++ . '.';
            })
            ->addColumn('roles', function ($item) {
                $roles = '';
                foreach ($item['roles'] as $role) {
                    if ($role == 'superadmin') {
                        $roles .= '<span class="badge rounded-pill bg-label-primary">' . $role . '</span>';
                    } elseif ($role == 'admin') {
                        $roles .= '<span class="badge rounded-pill bg-label-success">' . $role . '</span>';
                    } elseif ($role == 'user') {
                        $roles .= '<span class="badge rounded-pill bg-label-info">' . $role . '</span>';
                    }
                    $roles .= ' ';
                }
                return $roles;
            })
            ->addColumn('action', function ($item) {
                // return response()->json($item['default_permission']);

                //chek if default permission is true or false
                if ($item['default_permission'] == 'yes') {
                    $btn = '<span class="badge rounded-pill bg-label-secondary">
                        No Action
                    </span>';
                    return '<div class="text-center"> ' . $btn . '</div>';
                } else {
                    $editButton = '<button type="button" class="btn btn-label-primary waves-effect" title="Edit" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-id="' . $item['id'] . '">
                    <i class="mdi mdi-pencil-outline"></i></button>';
                    $deleteButton = '<button type="button" class="btn btn-label-danger waves-effect" title="Delete" id="deletePermissionButton" data-id="' . $item['id'] . '">
                    <i class="mdi mdi-trash-can-outline"></i></button>';

                    return '<div class="text-center"> ' . $editButton . ' ' . $deleteButton . '</div>';
                }
            })
            ->rawColumns(['roles', 'action'])
            ->make(true);

    }

    public function index()
    {
        try {
            $data = $this->getPageData();

            if (request()->ajax()) {
                return $this->getAjaxResponse();
            }

            // return response()->json($this->getAjaxResponse());
            return view($this->prefix . '.index', $data);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(PermissionStoreRequest $request)
    {
        try {
            $request->validated();
            $request->merge([
                'name' => ucwords($request->name),
                'module_name' => ucwords($request->module_name),
            ]);

            $permission = Permission::create([
                'name' => $request->name,
                'module_name' => $request->module_name,
                'guard_name' => 'web',
            ]);

            $permission->syncRoles('superadmin');

            return response()->json([
                'status' => 'success',
                'message' => 'Permission created successfully',
                'data' => [
                    'permission' => $permission,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission failed to create',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Permission retrieved successfully',
                'data' => [
                    'permission' => $permission,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission failed to retrieve',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    public function update(PermissionUpdateRequest $request, $id)
    {
        try {
            // Validate the request
            $request->validated();

            // Capitalize name and module_name
            $request->merge([
                'name' => ucwords($request->name),
                'module_name' => ucwords($request->module_name),
            ]);

            $permission = Permission::findOrFail($id);

            // Check if the name is being changed
            if ($permission->name != $request->name) {
                // Validate for unique name
                $request->validate([
                    'name' => 'required|unique:permissions,name',
                ]);
            }

            // Update the permission
            $permission->update([
                'name' => $request->name,
                'module_name' => $request->module_name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Permission updated successfully',
                'data' => [
                    'permission' => $permission,
                ],
            ], 200);

        } catch (QueryException $e) {
            // Handle database-related errors
            return response()->json([
                'status' => 'error',
                'message' => 'Permission failed to update',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Permission failed to update',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission deleted successfully',
                'data' => [
                    'permission' => $permission,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission failed to delete',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }

}
