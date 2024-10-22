<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiService
{
    public function getInstagramInfo(string $userName): array
    {
        $instagramInfo = [];

        $baseUrl = config('app.instagram_base_url');
        $businessId = config('app.business_id');
        $accessToken = config('app.instagram_token');

        $client = new Client();
        $url = $baseUrl . "/{$businessId}?fields=business_discovery.username({$userName}){media.limit(10){media_url,timestamp,username}}&access_token={$accessToken}";

        try {
            while ($url) {
                $response = $client->request('GET', $url);
                $data = json_decode($response->getBody(), true);

                if (isset($data['business_discovery']['media']['data'])) {
                    foreach ($data['business_discovery']['media']['data'] as $media) {
                        if (isset($media['media_url']) && isset($media['timestamp'])) {
                            $instagramInfo[] = [
                                'media_url' => $media['media_url'],
                                'time_stamp' => Carbon::parse($media['timestamp'])->format('Y年m月d日 H時i分s秒'),
                            ];
                        }
                    }
                }

                // API制限にひっかかるのでコメントアウト
                // if (isset($data['business_discovery']['media']['paging']['cursors']['after'])) {
                //     $after = $data['business_discovery']['media']['paging']['cursors']['after'];
                //     $url = $baseUrl . "/{$businessId}?fields=business_discovery.username({$userName}){media.limit(2).after({$after}){media_url,timestamp,username}}&access_token={$accessToken}";
                // } else {
                $url = null;
                //
            }
        } catch (Exception $e) {
            throw new Exception();
        }

        return $instagramInfo;
    }
}
