<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('web.index', ['services' => Service::all()]);
    }

    public function addOrder(CreateOrderRequest $request): Redirect
    {
        $data = $request->validated();


    }
}
