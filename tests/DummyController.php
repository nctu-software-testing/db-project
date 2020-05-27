<?php


namespace Tests;


class DummyController extends \App\Http\Controllers\BaseController
{

    public function __construct()
    {
        parent::__construct('testing');
    }

    public function exportedCheckCaptcha()
    {
        return parent::checkCaptcha();
    }
}
