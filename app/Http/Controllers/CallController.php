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
        $date = $request->input('date') ? : '2020-01-27';
        $perPage = $request->input('onPage') ? : 25;

        $data = $callService->getDataPagedByDate($date,$page,$perPage);
        return inertia('Dashboard',[
            'date' => $data['date'],
            'calls' => $data['calls'],
            'count' => $data['count'],
            'page' => $data['page'],
            'onPage' =>$data['onPage']
        ]);
    }

    public function overloadsIndex(Request $request, CallService $callService) {

        $date = $request->input('date') ? : Carbon::parse('2020-01-27');
        $page = $request->input('page') ? : 1;
        $limit = $request->input('limit') ? : 100;

        $data = $callService->getOverloadsByDate($date,$limit,$page);

        return inertia('Overloads',[
            'date' => $date,
            'limit' => $limit,
            'data' => $data
        ]);
    }

    public function maxLoadsIndex(Request $request, CallService $callService) {

        $date = $request->input('date') ? : Carbon::parse('2020-01-27');
        $page = $request->input('page') ? : 1;
        $limit = $request->input('limit') ? : 100;

        $data = $callService->getMaxLoadsByDate($date,$limit,$page);

        return inertia('MaxLoads',[
            'date' => $date,
            'limit' => $limit,
            'data' => $data
        ]);
    }

    public function cacheClear(CallService $callService) {
        $callService->cacheClear();
        return redirect()->route('dashboard');
    }
}
