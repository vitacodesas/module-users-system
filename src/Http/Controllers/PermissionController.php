<?php

namespace Vitacode\ModuleUsersSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vitacode\ModuleUsersSystem\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Crear un nuevo permiso.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);
        $permission = Permission::create(['name' => $request->name]);

        return $this->responseCustom(true, [
            'permission' => $permission,
        ], 'Permiso creado exitosamente.', Response::HTTP_CREATED);
    }

    /**
     * Listar todos los permisos.
     */
    public function index()
    {
        $permissions = Permission::all();

        return $this->responseCustom(true, [
            'permissions' => $permissions,
        ], 'Permisos listados exitosamente.', Response::HTTP_OK);
    }
}