<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ApiService;

class TopController extends Controller
{
    protected ApiService $apiService;

    public function __construct(
        ApiService $apiService
    ) {
        $this->apiService = $apiService;
    }

    public function index(): View
    {
        return view('index');
    }

    public function fetch(Request $request): array
    {
        $bussinessId = $request->request->get('bussiness-id');
        $instagramInfo = $this->apiService->getInstagramInfo($bussinessId);

        return $instagramInfo;
    }
}
