<?php

namespace App\Services\Call;

use Exception;
use Illuminate\Support\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CallService {

    const OVERLOAD_RATE_PER_SECOND = 100;
    const OVERLOAD_RATE_PER_MINUTE = 100;

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
        Cache::flush();
    }

    public static function paginateData(Collection $data, int $page = 1, int $perPage) : Collection {

        $dataCount = $data->count();
        $pageCount = ceil($dataCount/$perPage);

        if($page > $pageCount) $page  = 1;

        return collect([
            'data' => $data
                ->skip(($perPage*($page-1)+1))
                ->take($perPage-1),
            'page' => $page
        ]);
    }

    public function getDataPagedByDate($date,int $page,int $perPage) : array {

        $data = collect($this->data)->sortBy([
            ['start_date_time','desc']
        ]);

        $day_start = Carbon::parse($date);
        $day_end = Carbon::parse($date)->addDay();

        $filered_data = collect($data)
        ->filter(function($item) use ($date,$day_start,$day_end){

            $item_start_date_time = Carbon::parse($item['start_date_time']);

            return
                ($item_start_date_time->gte($day_start))
                && ($item_start_date_time->lt($day_end));
        });

        $pagination = self::paginateData($filered_data,$page,$perPage)->toArray();

        return [
            'date' => $date,
            'date_start' => $day_start->toDateTimeString(),
            'date_end' => $day_end->toDateTimeString(),
            'calls' => $pagination['data'],
            'count' => $filered_data->count(),
            'page'  => $pagination['page'],
            'onPage' => $perPage
        ];
    }

    public function getOverloadsByDate(string $date,int $limit,int $page,int $perPage) :array {

        $ts_start = Carbon::parse($date)->timestamp;
        $ts_end = Carbon::parse($date)->addDay()->timestamp;
        $data = collect($this->data);
        $cacheDate = Carbon::parse($date)->format('Ymd');

        if(Cache::has("overloads_{$cacheDate}_{$limit}")) {

            $report_data = collect(Cache::get("overloads_{$cacheDate}_{$limit}"));

        } else {

            $report = collect();

            $seconds = collect(range($ts_start,$ts_end))
            ->each(function($second) use ($data,&$report) {

                $calls = $data->filter(function($item) use ($second,&$report) {

                    return $item['start_date_ts'] <= $second && $item['end_date_ts'] >= $second;

                });

                $report->put($second,$calls);
            });

            $report_data = $report
            ->filter(function($calls,$second) use ($limit) {
                return $calls->count() > $limit;
            })
            ->transform(function($calls,$second) {
                return [
                    'time' => Carbon::parse($second)->toDateTimeString(),
                    'ts' => $second,
                    'count' => $calls->count()
                ];
            })
            ->values()
            ->sortBy([
                ['ts','desc']
            ]);
            Cache::put("overloads_{$cacheDate}_{$limit}", $report_data->toArray(), now()->addMinutes(60));
        }

        $pagination = self::paginateData($report_data,$page,$perPage)->toArray();

        return [
            'date' => $date,
            'limit' => $limit,
            'ts_start' => $ts_start,
            'ts_end' => $ts_end,
            'data' => $pagination['data'],
            'count' => $report_data->count(),
            'page'  => $pagination['page'],
            'onPage' => $perPage
        ];
    }

    public function getMaxLoadsByDate($date,int $limit,int $page = 1,int $perPage = 25) : array {

        $day_time = Carbon::parse($date);
        $day_end = Carbon::parse($date)->addDay();
        $data = collect($this->data);
        $cacheDate = $day_time->format('Ymd');

        if(Cache::has("maxloads_{$cacheDate}_{$limit}")) {

            $report = collect(Cache::get("maxloads_{$cacheDate}_{$limit}"));
        }

        else {

            $report = collect();

            while ($day_time->lt($day_end)) {

                $calls = $data->filter(function($item) use ($day_time) {

                    $minute_end = Carbon::parse($day_time->toDateTimeString())->addMinute();

                    return ($item['start_date_ts'] < $day_time->timestamp && $item['end_date_ts'] >= $day_time->timestamp)
                    || ($item['start_date_ts'] >= $day_time->timestamp && $item['start_date_ts'] < $minute_end->timestamp);

                });

                $calls_count = $calls->count();

                $report->put($day_time->format('Y-m-d H:i'),
                    [
                        'time' => $day_time->format('Y-m-d H:i'),
                        'count' => $calls_count,
                        'status' => $calls_count > $limit ? 'overload' : 'ok'
                    ]
                );

                $day_time->addMinute();
            }
            $report = $report->sortKeysDesc()->values();

            Cache::put("maxloads_{$cacheDate}_{$limit}", $report->toArray(), now()->addMinutes(60));
        }

        $pagination = self::paginateData($report,$page,$perPage)->toArray();

        return [
            'date' => $date,
            'limit' => $limit,
            'data' => $pagination['data'],
            'count' => $report->count(),
            'page'  => $pagination['page'],
            'onPage' => $perPage
        ];
    }
}
