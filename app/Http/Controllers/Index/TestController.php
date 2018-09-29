<?php
namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Vinkla\Hashids\Facades\Hashids;


class TestController extends Controller{
    use Helpers;
    protected  $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function test($id){
//        try{
//            $user = $this->user->findOrfail($id);
            $user = $this->user->find($id);
//        }catch (ModelNotFoundException  $e){
//            dd(get_class_methods($e)); // lists all available methods for exception object
//            dd($e);
//        }
        return $this->response->item($user,new UserTransformer());
    }
}