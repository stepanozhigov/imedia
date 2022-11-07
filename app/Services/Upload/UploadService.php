<?php

namespace App\Services\Upload;

use Illuminate\Support\Facades\Storage;

class UploadService {

    const STORAGE_FILE_PATH = 'json/data.json';

    protected $fileExists;

    public function __construct()
    {
        $this->fileExists = Storage::disk('local')->exists(self::STORAGE_FILE_PATH) ? true : false;
    }

    public function fileExists() {
        return $this->fileExists;
    }
}
