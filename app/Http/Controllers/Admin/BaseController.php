<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class BaseController extends Controller
{
    use Helpers;
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login','register']]);
    }

    public  function errorBadRequest($data)
    {
        return json_encode($data);
    }
}