<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

class BaseController extends Controller
{
    use Helpers;
    public function __construct()
    {
        $this->middleware('auth:index', ['except' => ['login']]);
    }

    public  function errorBadRequest($validator)
    {
        $result = [];
        $messages = $validator->errors()->toArray();

        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }
        return $result;
    }
}