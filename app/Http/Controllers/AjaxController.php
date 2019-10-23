<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
  public function index() {
    $currencyRates = DB::table('currency_rates')->select('quote', 'rate')->get();

    return response()->json(array('data'=> $currencyRates), 200);
   }
}
