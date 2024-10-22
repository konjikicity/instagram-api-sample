<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ApiService;
use Illuminate\Http\RedirectResponse;
use Exception;

class TopController extends Controller
{
    protected ApiService $apiService;
    private $error = null;
    private array $instagramInfo = [];

    public function __construct(
        ApiService $apiService
    ) {
        $this->apiService = $apiService;
    }

    public function index(): View
    {
        return view('index', [
            'instagramInfo' => $this->instagramInfo,
            'error' => $this->error,
        ]);
    }

    public function fetch(Request $request): View
    {
        $bussinessId = $request->request->get('bussiness-id');

        try {
            $this->instagramInfo = $this->apiService->getInstagramInfo($bussinessId);
        } catch (Exception $e) {
            $this->error = 'apiでエラーが発生しました。';
        }

        return view('index', [
            'instagramInfo' => $this->instagramInfo,
            'error' => $this->error,
        ]);
    }
}
