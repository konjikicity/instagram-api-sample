<?php

namespace App\Console\Commands\GA;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SendGAShopFollow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-ga-shop-follow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GA4イベントテスト送信用コマンド(店舗フォロー)';

    /**
     * データを各店舗10件ずつ送信
     *
     * フォロー店舗名
     * エリア
     * 会員番号
     * 都道府県
     * 年齢
     * 継続利用日数
     */
    public function handle()
    {

        $firebase_app_id = config('app.firebase_app_id');
        $measurement_api_secret = config('app.measurement_api_secret');

        $client = new Client();
        $url = "https://www.google-analytics.com/mp/collect?firebase_app_id={$firebase_app_id}&api_secret={$measurement_api_secret}";

        $shops =  $this->getShops();
        $areaPref = $this->getAreaPref();
        $loopCount = rand(1, 100);

        foreach ($shops as $shop) {
            for ($i = 0; $i < $loopCount; $i++) {
                $lf_member_number = rand(100000, 999999);
                $app_instance_id = bin2hex(random_bytes(16));
                $age = rand(18, 65);
                $continue_use_date = rand(1, 365);

                $randomAreaPref = $areaPref[array_rand($areaPref)];

                $data = [
                    'app_instance_id' => $app_instance_id,
                    'events' => [
                        [
                            'name' => 'shop_follow',
                            'params' => [
                                'shop_name' => $shop['name'],
                                'shop_area' => $shop['area'],
                                'lf_member_number' => $lf_member_number,
                                'area' => $randomAreaPref['area'],
                                'pref' => $randomAreaPref['pref'],
                                'age' => $age,
                                'continue_use_date' => $continue_use_date,
                                "session_id" => "123",
                                "engagement_time_msec" => "100",
                            ]
                        ]
                    ]
                ];

                try {
                    $response = $client->post($url, [
                        'json' => $data
                    ]);

                    if ($response->getStatusCode() == 204) {
                        $count = $i + 1;
                        echo "イベントが正常に送信されました: {$shop['name']} ({$count}回目)\n";
                    } else {
                        echo "エラーが発生しました: " . $response->getBody();
                    }
                } catch (\Exception $e) {
                    echo "リクエストに失敗しました: " . $e->getMessage();
                }
            }
        }
    }

    private function getShops(): array
    {
        $shops = [
            [
                'name' => 'LOGOS SHOP 札幌店',
                'area' => '北海道'
            ],
            [
                'name' => 'LOGOS SHOP 大阪店',
                'area' => '近畿'
            ],
            [
                'name' => 'LOGOS SHOP 東京店',
                'area' => '関東'
            ],
            [
                'name' => 'LOGOS SHOP 越谷レイクタウンアウトレット店',
                'area' => '関東'
            ],
            [
                'name' => 'LOGOS SHOP 山口店',
                'area' => '中国',
            ],
            [
                'name' => 'LOGOS SHOP 静岡清水店',
                'area' => '東海',
            ],
            [
                'name' => 'LOGOS SHOP ららぽーと沼津',
                'area' => '東海',
            ],
            [
                'name' => 'フォレオ大津一里山店',
                'area' => '近畿',
            ],
            [
                'name' => 'LOGOS SHOP ピエリ守山店',
                'area' => '近畿',
            ],
            [
                'name' => 'LOGOS SHOP & CAFE ロゴスランド店',
                'area' => '北関東・甲信',
            ],
        ];

        return $shops;
    }

    private function getAreaPref()
    {
        $area = [
            [
                'area' => '北海道',
                'pref' => '北海道'
            ],
            [
                'area' => '東北',
                'pref' => '青森県'
            ],
            [
                'area' => '東北',
                'pref' => '岩手県'
            ],
            [
                'area' => '東北',
                'pref' => '宮城県'
            ],
            [
                'area' => '東北',
                'pref' => '秋田県'
            ],
            [
                'area' => '東北',
                'pref' => '山形県'
            ],
            [
                'area' => '東北',
                'pref' => '福島県'
            ],
            [
                'area' => '関東',
                'pref' => '茨城県'
            ],
            [
                'area' => '関東',
                'pref' => '栃木県'
            ],
            [
                'area' => '関東',
                'pref' => '群馬県'
            ],
            [
                'area' => '関東',
                'pref' => '埼玉県'
            ],
            [
                'area' => '関東',
                'pref' => '千葉県'
            ],
            [
                'area' => '関東',
                'pref' => '東京都'
            ],
            [
                'area' => '関東',
                'pref' => '神奈川県'
            ],
            [
                'area' => '北関東・甲信',
                'pref' => '新潟県'
            ],
            [
                'area' => '北関東・甲信',
                'pref' => '富山県'
            ],
            [
                'area' => '北関東・甲信',
                'pref' => '石川県'
            ],
            [
                'area' => '北関東・甲信',
                'pref' => '福井県'
            ],
            [
                'area' => '北関東・甲信',
                'pref' => '山梨県'
            ],
            [
                'area' => '北関東・甲信',
                'pref' => '長野県'
            ],
            [
                'area' => '近畿',
                'pref' => '滋賀県'
            ],
            [
                'area' => '近畿',
                'pref' => '京都府'
            ],
            [
                'area' => '近畿',
                'pref' => '大阪府'
            ],
            [
                'area' => '近畿',
                'pref' => '兵庫県'
            ],
            [
                'area' => '近畿',
                'pref' => '奈良県'
            ],
            [
                'area' => '近畿',
                'pref' => '和歌山県'
            ],
            [
                'area' => '東海',
                'pref' => '岐阜県'
            ],
            [
                'area' => '東海',
                'pref' => '静岡県'
            ],
            [
                'area' => '東海',
                'pref' => '愛知県'
            ],
            [
                'area' => '東海',
                'pref' => '三重県'
            ],
            [
                'area' => '中国',
                'pref' => '鳥取県'
            ],
            [
                'area' => '中国',
                'pref' => '島根県'
            ],
            [
                'area' => '中国',
                'pref' => '岡山県'
            ],
            [
                'area' => '中国',
                'pref' => '広島県'
            ],
            [
                'area' => '中国',
                'pref' => '山口県'
            ],
            [
                'area' => '四国',
                'pref' => '徳島県'
            ],
            [
                'area' => '四国',
                'pref' => '香川県'
            ],
            [
                'area' => '四国',
                'pref' => '愛媛県'
            ],
            [
                'area' => '四国',
                'pref' => '高知県'
            ],
            [
                'area' => '九州',
                'pref' => '福岡県'
            ],
            [
                'area' => '九州',
                'pref' => '佐賀県'
            ],
            [
                'area' => '九州',
                'pref' => '長崎県'
            ],
            [
                'area' => '九州',
                'pref' => '熊本県'
            ],
            [
                'area' => '九州',
                'pref' => '大分県'
            ],
            [
                'area' => '九州',
                'pref' => '宮崎県'
            ],
            [
                'area' => '九州',
                'pref' => '鹿児島県'
            ],
            [
                'area' => '九州',
                'pref' => '沖縄県'
            ]
        ];

        return $area;
    }
}
