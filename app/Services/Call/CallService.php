<?php

namespace App\Services\Call;

use Exception;
use Illuminate\Support\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CallService {

    protected $json;
    protected $data;

    public function __construct() {

        if(Cache::has('data')) {
            $this->data = Cache::get('data');
        }
        else {
            $this->json = $this->_loadJson();
            if($this->json) {
                $this->data = $this->_processJson($this->json);
                Cache::put('data', $this->data, now()->addMinutes(60));
            }
        }
    }

    private function _loadJson() : ?array {
        $file_exists = Storage::disk('local')->exists('json/data.json');
        if($file_exists) {
            return json_decode(Storage::disk('local')->get('json/data.json'), true);
        }
        return null;
    }

    private function _processJson(array $json) : array {

        $collection = collect($json)
            ->transform(function($item) {
                $item['end_date_time'] = Carbon::parse($item['start_date_time'])
                    ->addSeconds($item['duration_seconds'])
                    ->toDateTimeString();
                $item['start_date_ts'] = Carbon::parse($item['start_date_time'])->timestamp;
                $item['end_date_ts'] = Carbon::parse($item['end_date_time'])->timestamp;
                return $item;
            });
        return $collection->toArray();
    }

    public function getJson() {
        return $this->json;
    }

    public function getData() {
        return $this->data;
    }

    public function cacheClear() {
        if(Cache::has('data')) {
            $this->data = Cache::forget('data');
        }
    }

    public function getDataPagedByDate($date,int $page = 1,$perPage = 25) : array {

        $data = collect($this->data)->sortBy([
            ['start_date_time','desc']
        ]);

        if($date) {
            $day_start = Carbon::parse($date);
            $day_end = Carbon::parse($date)->addDay();

            $filered_data = collect($data)
            ->filter(function($item) use ($date,$day_start,$day_end){

                $item_start_date_time = Carbon::parse($item['start_date_time']);

                return
                    ($item_start_date_time->gte($day_start))
                    && ($item_start_date_time->lt($day_end));
            });

            //paginate
            $count = $filered_data->count();


            return [
                'date' => $date,
                'date_start' => $day_start->toDateTimeString(),
                'date_end' => $day_end->toDateTimeString(),
                'calls' => $filered_data->toArray(),
                'count' => $count
            ];
        }

        //paginate

        return [
            'date' => null,
            'date_start' => null,
            'date_end' => null,
            'calls' => $this->data,
            'count' => count($this->data)
        ];
    }

    public function getOverloadsByDate($date,int $page = 1,$perPage = 25) {

    }
}
