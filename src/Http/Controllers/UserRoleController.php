<?php

namespace Vitacode\ModuleUsersSystem\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User; // Asegúrate de que sea el modelo User correcto
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Vitacode\ModuleUsersSystem\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    /**
     * Asignar un rol a un usuario.
     */
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name', // Asegúrate de que el rol exista
        ]);

        $user = User::findOrFail($userId);
        $role = Role::findByName($request->role); // Busca el rol por nombre

        $user->assignRole($role); // Asigna el rol al usuario

        return $this->responseCustom(true, [
            'user' => $user,
            'role' => $role,
        ], 'Rol asignado exitosamente.', Response::HTTP_OK);
    }

    /**
     * Quitar un rol a un usuario.
     */
    public function removeRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name', // Asegúrate de que el rol exista
        ]);

        $user = User::findOrFail($userId);
        $role = Role::findByName($request->role); // Busca el rol por nombre

        $user->removeRole($role); // Quita el rol del usuario


        return $this->responseCustom(true, [
            'user' => $user,
            'role' => $role,
        ], 'Rol eliminado exitosamente.', Response::HTTP_OK);
    }
}