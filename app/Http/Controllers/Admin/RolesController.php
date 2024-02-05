<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->prefix = 'admin.roles';
        $this->pageName = 'Roles';

        $this->middleware('permission:Role', ['only' => 'index']);
        $this->middleware('permission:Role Show', ['only' => 'show']);
        $this->middleware('permission:Role Store', ['only' => 'store']);
        $this->middleware('permission:Role Update', ['only' => 'update']);
        $this->middleware('permission:Role Destroy', ['only' => 'destroy']);
    }

    private function getPageData()
    {
        $data = [
            'pageTitle' => $this->pageName,
            'pageDescription' => '',
            'breadcrumbItems' => [
                ['name' => 'Role', 'url' => route('roles.index')],
            ],
        ];

        return $data;
    }

    private function getAjaxResponse()
    {
        $roles = Role::orderBy('id', 'desc')->get()->map(function ($role) {
            $permissionNames = $role->permissions->pluck('name')->toArray();
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'permissions' => $permissionNames,
            ];
        });

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('no', function ($item) {
                static $no = 1;
                return $no++ . '.';
            })
            ->addColumn('action', function ($item) {
                $btn = '';
                $btn .= '<a href="' . route('roles.show', $item['id']) . '" class="btn btn-label-info waves-effect"><i class="mdi mdi-eye"></i></a>';
                return '<div class="text-center"> ' . $btn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAjaxResponse();
        }

        $data = $this->getPageData();
        return view($this->prefix . '.index', $data);
    }

    private function getModulePermissions()
    {
        $permissions = Permission::get();
        $modules = Permission::selectRaw('module_name')->groupBy('module_name')->get();
        $modulePermissions = [];

        foreach ($modules as $module) {
            $modulePermissions[$module->module_name] = $permissions
                ->where('module_name', $module->module_name)
                ->map(function ($perm) {
                    return ['id' => $perm->id, 'name' => $perm->name];
                })
                ->toArray();
        }

        return $modulePermissions;
    }

    public function store(RoleStoreRequest $request)
    {
        try {
            $request->validated();
            $request->merge([
                'name' => ucwords($request->name),
            ]);

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Roles created successfully',
                'data' => [
                    'role' => $role,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Role failed to create',
                'data' => [
                    'error' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $data = $this->getPageData();
        $data['breadcrumbItems'][] = ['name' => 'Detail ' . $role->name, 'url' => route('roles.show', $id)];
        $data['role'] = $role;

        $data['rolePermissions'] = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('permission_id')
            ->all();
        $data['modulePermissions'] = $this->getModulePermissions();

        return view($this->prefix . '.show', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => ucwords($request->name),
            'guard_name' => 'web',
        ]);
        $role->syncPermissions($request->permission);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }
}
