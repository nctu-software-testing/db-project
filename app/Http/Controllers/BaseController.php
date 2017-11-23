<?php

namespace App\Http\Controllers;

class BaseController extends \Illuminate\Routing\Controller
{
    protected function result($data, $success = true)
    {
        return response()->json([
            'result' => $data,
            'success' => $success,
        ]);
    }
}