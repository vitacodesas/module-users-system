<?php

namespace Vitacode\ModuleUsersSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vitacode\ModuleUsersSystem\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Crear un nuevo rol.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        return $this->responseCustom(true, [
            'role' => $role,
        ], 'Rol creado exitosamente.', Response::HTTP_CREATED);
    }

    /**
     * Listar todos los roles.
     */
    public function index()
    {
        $roles = Role::all();

        return $this->responseCustom(true, [
            'roles' => $roles,
        ], 'Roles listados exitosamente.', Response::HTTP_OK);
    }

    /**
     * Asignar permisos a un rol.
     */
    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return $this->responseCustom(true, [
            'role' => $role,
            'permissions' => $permissions,
        ], 'Permisos asignados exitosamente.', Response::HTTP_OK);
    }
}