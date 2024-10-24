<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Services\ApiService;
use Exception;

class YouTubeController extends Controller
{
    protected ApiService $apiService;

    public function __construct(
        ApiService $apiService
    ) {
        $this->apiService = $apiService;
    }

    public function index(): View
    {
        $error = null;
        $youtubeInfo = [];

        try {
            $youtubeInfo = $this->apiService->getYouTubeInfo();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return view('youtube', [
            'youtubeInfo' => $youtubeInfo,
            'error' => $error,
        ]);
    }
}
