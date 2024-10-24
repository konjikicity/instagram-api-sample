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
        $url = $baseUrl . "/{$businessId}?fields=business_discovery.username({$userName}){media.limit(10){media_url,media_type,permalink,thumbnail_url,timestamp,username}}&access_token={$accessToken}";

        // 最新10件のみ表示(1時間に200件までしか取得できないため)
        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            if (isset($data['business_discovery']['media']['data'])) {
                foreach ($data['business_discovery']['media']['data'] as $media) {
                    $instagramInfo[] = [
                        'media_url' => isset($media['media_url']) ? $media['media_url'] : null,
                        'time_stamp' => Carbon::parse($media['timestamp'])->format('Y年m月d日 H時i分s秒'),
                        'permalink' => $media['permalink'],
                        'thumbnail_url' => isset($media['thumbnail_url']) ? $media['thumbnail_url'] : null,
                        'media_type' => $media['media_type'],
                    ];
                }
            }
        } catch (Exception $e) {
            throw new Exception('Instagram情報の取得中にエラーが発生しました: ' . $e->getMessage());
        }

        return $instagramInfo;
    }

    public function getYouTubeInfo()
    {
        $youtubeInfo = [];

        $baseUrl = config('app.youtube_base_url'); // YouTubeのベースURL (例: https://www.googleapis.com/youtube/v3)
        $apiKey = config('app.youtube_token');    // YouTube Data APIキー
        $channelId = config('app.youtube_channel_id'); // 対象のYouTubeチャンネルID

        // 現在から1年前の日付をUTC形式（Z付き）で取得
        $oneYearAgo = Carbon::now()->subYear()->format('Y-m-d\TH:i:s\Z');

        $client = new Client();
        $nextPageToken = null;

        while (true) {
            $url = $baseUrl . "/search?part=snippet&channelId={$channelId}&maxResults=50&order=date&type=video&publishedAfter={$oneYearAgo}&key={$apiKey}";
            if ($nextPageToken) {
                $url .= "&pageToken={$nextPageToken}";
            }

            try {
                $response = $client->request('GET', $url);
                $data = json_decode($response->getBody(), true);

                if (isset($data['items'])) {
                    foreach ($data['items'] as $video) {
                        $snippet = $video['snippet'];
                        $videoId = $video['id']['videoId']; // 動画IDを取得
                        $youtubeInfo[] = [
                            'title' => $snippet['title'],
                            'thumbnail' => $snippet['thumbnails']['high']['url'],
                            'published_at' => Carbon::parse($snippet['publishedAt'])->format('Y年m月d日 H時i分s秒'),
                            'video_id' => $videoId,
                            'video_url' => "https://www.youtube.com/watch?v={$videoId}", // 動画のURL
                        ];
                    }
                }

                $nextPageToken = isset($data['nextPageToken']) ? $data['nextPageToken'] : null;

                if (!$nextPageToken) {
                    break;
                }
            } catch (Exception $e) {
                throw new Exception('YouTube情報の取得中にエラーが発生しました: ' . $e->getMessage());
            }
        }

        return $youtubeInfo;
    }
}
