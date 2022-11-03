<?php

namespace App\Repositories\Call;

use Exception;
use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CallRepository {

    protected $json;

    public function __construct() {
        $this->json = $this->_loadJson();
    }

    private function _loadJson() {
        $file_exists = Storage::disk('local')->exists('json/data.json');
        if($file_exists) {
            return json_decode(Storage::disk('local')->get('json/data.json'), true);
        }
        return null;
    }

    public function getJson() {
        return $this->json;
    }



    // public static function getCallsByDate($date) : Collection {


    //     return collect();

    // }
}
