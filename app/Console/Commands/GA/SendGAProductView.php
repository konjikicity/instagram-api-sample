<?php

namespace App\Console\Commands\GA;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SendGAProductView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-ga-product-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GA4イベントテスト送信用コマンド(製品閲覧)';

    /**
     * 送信データ
     *
     * 製品名
     * 大カテゴリ
     * 中
     * 会員番号
     * 都道府県
     * 年齢
     * 継続利用日数
     * 遷移元
     */
    public function handle()
    {

        $firebase_app_id = config('app.firebase_app_id');
        $measurement_api_secret = config('app.measurement_api_secret');

        $client = new Client();
        $url = "https://www.google-analytics.com/mp/collect?firebase_app_id={$firebase_app_id}&api_secret={$measurement_api_secret}";

        $areaPref = $this->getAreaPref();
        $transitionSources = $this->getTransitionSource();
        $products = $this->getProducts();

        foreach ($products as $product) {
            $loopCount = rand(1, 100);
            for ($i = 0; $i < $loopCount; $i++) {
                $lf_member_number = rand(100000, 999999);
                $app_instance_id = bin2hex(random_bytes(16));
                $age = rand(18, 65);
                $continue_use_date = rand(1, 365);
                $lfMemberId = rand(1, 100000000);

                $randomAreaPref = $areaPref[array_rand($areaPref)];

                $data = [
                    'app_instance_id' => $app_instance_id,
                    'events' => [
                        [
                            'name' => 'product_view',
                            'params' => [
                                'product_id' => $product['id'],
                                'content_name' => $product['name'],
                                'large_category' => $product['large_category'],
                                'medium_category' => $product['medium_category'],
                                'small_category' => $product['small_category'],
                                'lf_member_id' => $lfMemberId,
                                'lf_member_number' => $lf_member_number,
                                'area' => $randomAreaPref['area'],
                                'pref' => $randomAreaPref['pref'],
                                'age' => $age,
                                'continue_use_date' => $continue_use_date,
                                'transition_source' => $transitionSources[array_rand($transitionSources)],
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
                        echo "イベントが正常に送信されました: {$product['name']} ({$count}回目)\n";
                    } else {
                        echo "エラーが発生しました: " . $response->getBody();
                    }
                } catch (\Exception $e) {
                    echo "リクエストに失敗しました: " . $e->getMessage();
                }
            }
        }
    }

    private function getTransitionSource(): array
    {
        $transitionSources = [
            '製品TOP',
            'Push通知'
        ];

        return $transitionSources;
    }

    private function getProducts(): array
    {
        $products = [
            [
                'id' => 1,
                'name' => 'プレミアム デビルブロックルーム EX-BD（グレートドゥーブルG2・プラス XL-BD用）',
                'large_category' => 'ギア',
                'medium_category' => 'テント',
                'small_category' => '2ルーム'
            ],
            [
                'id' => 2,
                'name' => 'プレミアム PANEL グレートドゥーブルG2・プラス XL-BD',
                'large_category' => 'ギア',
                'medium_category' => 'テント',
                'small_category' => '2ルーム'
            ],
            [
                'id' => 3,
                'name' => '【秋セット】丸洗いスランバーシュラフ・-2　2個セット',
                'large_category' => 'ギア',
                'medium_category' => '寝具',
                'small_category' => 'シュラフ'
            ],
            [
                'id' => 4,
                'name' => '【秋セット】丸洗いやわらか あったかシュラフ・-2　2個セット',
                'large_category' => 'ギア',
                'medium_category' => '寝具',
                'small_category' => 'シュラフ'
            ],
            [
                'id' => 5,
                'name' => '氷点下リカバリー・クールボディタオル',
                'large_category' => 'アパレル',
                'medium_category' => 'アクセサリー',
                'small_category' => 'タオル・てぬぐい・バンダナ'
            ],
            [
                'id' => 6,
                'name' => 'CAMP LOGOS Leeコラボフリージーパンツ',
                'large_category' => 'アパレル',
                'medium_category' => 'ボトムス',
                'small_category' => 'パンツ'
            ],
            [
                'id' => 7,
                'name' => 'アネモスシェル（Family)',
                'large_category' => 'アパレル',
                'medium_category' => 'アウター',
                'small_category' => 'ジャケット'
            ],
            [
                'id' => 8,
                'name' => 'ロゴスのえほん 水くん',
                'large_category' => 'コレクション',
                'medium_category' => 'カタログ',
                'small_category' => 'その他'
            ],
            [
                'id' => 9,
                'name' => 'ロゴスのえほん 火くん',
                'large_category' => 'コレクション',
                'medium_category' => 'カタログ',
                'small_category' => 'その他'
            ],
            [
                'id' => 10,
                'name' => 'ロゴスのえほん 木くん',
                'large_category' => 'コレクション',
                'medium_category' => 'カタログ',
                'small_category' => 'その他'
            ],
            [
                'id' => 11,
                'name' => 'Loopadd どこでもホルダー',
                'large_category' => 'ギア',
                'medium_category' => '寝具',
                'small_category' => 'チェアカバー',
            ],
            [
                'id' => 12,
                'name' => '【秋セット】7075キュービックチェア（レッド）　2個セット',
                'large_category' => 'ギア',
                'medium_category' => '寝具',
                'small_category' => 'チェア・ベンチ',
            ]
        ];

        return $products;
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
