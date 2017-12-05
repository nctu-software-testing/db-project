<?php

namespace App\Http\Controllers;

class BaseController extends \Illuminate\Routing\Controller
{
    protected $controllerName = 'base';

    protected function __construct($name = 'base')
    {
        $this->controllerName = $name;
        $this->setShareVariables('pageName', $name);
    }

    protected function setShareVariables($key, $val)
    {
        view()->share($key, $val);
        return $this;
    }

    protected function result($data, $success = true)
    {
        return response()->json([
            'result' => $data,
            'success' => $success,
        ]);
    }

    protected function setMessage($message, $type = 'info'){
        return session()->flash('log', [
            'message'   =>  $message,
            'type'  =>  $type
        ]);
    }
}