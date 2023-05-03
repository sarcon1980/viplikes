<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {

        $services = Service::query()
            ->with(['items', 'options'])
            ->whereNot('parent_id', 0)
            ->get();
        return view('test', ['services' => $services]);
    }
}
