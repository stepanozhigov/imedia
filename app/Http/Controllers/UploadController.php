<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index() {
        return inertia('Upload');
    }

    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|mimes:json,txt|max:2048'
        ]);
        $path = Storage::putFileAs(
            'json', $request->file('file'), 'data.json'
        );
        Cache::flush();
        return redirect()->route('dashboard');
    }
}
