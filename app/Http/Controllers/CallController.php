<?php

namespace App\Http\Controllers;

use App\Services\Call\CallService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;
use Carbon\Carbon;

class CallController extends Controller

{

    public function index(Request $request,CallService $callService) {

        $page = $request->input('page') ? : 1;

        $data = $callService->getDataPagedByDate($request->input('date'),$page);

        return inertia('Dashboard',[
            'data' => $data,
            'date' => $request->input('date')
        ]);
    }

    public function overloadsIndex(Request $request, CallService $callService) {

        $date = $request->input('date') ? : Carbon::parse('2020-01-27');
        $page = $request->input('page') ? : 1;
        $limit = $request->input('limit') ? : 50;

        $data = $callService->getOverloadsByDate($date,$limit,$page);

        return inertia('Overloads',[
            'limit' => $limit,
            'data' => $data
        ]);
    }

    public function cacheClear(CallService $callService) {
        $callService->cacheClear();
        return redirect()->route('dashboard');
    }
}
