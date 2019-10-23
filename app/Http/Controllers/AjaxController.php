<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
  public function index() {
    $currencyRates = DB::select('select quote, rate from currency_rates');

    return response()->json(array('data'=> $currencyRates), 200);
   }
}
