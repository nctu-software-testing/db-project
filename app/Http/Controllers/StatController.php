<?php

namespace App\Http\Controllers;

use App\Location;
use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;

class StatController extends BaseController
{
    public $paginate = 10;

    public function __construct()
    {
        parent::__construct('stat');
    }

}
