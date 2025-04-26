<?php

namespace Vitacode\ModuleUsersSystem\Http\Controllers;

use Illuminate\Http\Response;

class Controller extends \Illuminate\Routing\Controller
{
    public function responseCustom(Bool $status = true, $data = [], String $message = 'Acción procesada con éxito.', Int $code = Response::HTTP_OK)
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ], $code);
    }
}