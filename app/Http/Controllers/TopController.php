<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ApiService;
use Illuminate\Http\RedirectResponse;

class TopController extends Controller
{
    protected ApiService $apiService;

    public function __construct(
        ApiService $apiService
    ) {
        $this->apiService = $apiService;
    }

    public function index(Request $request): View
    {
        $instagramInfo = session('instagramInfo', []);

        return view('index', ['instagramInfo' => $instagramInfo]);
    }

    public function fetch(Request $request): RedirectResponse
    {
        $bussinessId = $request->request->get('bussiness-id');
        $instagramInfo = $this->apiService->getInstagramInfo($bussinessId);

        return redirect()->route('top.index')->with('instagramInfo', $instagramInfo);
    }
}
