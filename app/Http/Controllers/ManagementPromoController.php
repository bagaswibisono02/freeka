<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagementPromoController extends Controller
{
    function index(Request $request) {
        return view('management-promo.index');
    }
}
