<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Upload\UploadService;

class CheckDataFile
{

    protected $uploadService;

    public function __construct(UploadService $uploadService) {
        $this->uploadService = $uploadService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $file_exists = $this->uploadService->fileExists();
        if(!$file_exists) return redirect()->route('upload.get');

        return $next($request);
    }
}
