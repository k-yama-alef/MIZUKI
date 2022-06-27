<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ファサードを use する
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\AlefCommon;
use App\Facades\MZCommon;
use Carbon\Carbon;
use App\Http\Controllers\TestController;
class phpinfoController extends Controller
{
    /*-----------
    phpinfo処理
    -----------*/
    public function check(Request $req){
        return response(phpinfo());
    }
}