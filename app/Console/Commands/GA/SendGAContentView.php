<?php

namespace App\Console\Commands\GA;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SendGACountentView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-ga-countent-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GA4イベントテスト送信用コマンド(製品閲覧)';

    /**
     * 送信データ
     *
     * 記事名
     * 記事種別
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
        $articles = $this->getArticles();

        foreach ($articles as $article) {
            $loopCount = rand(1, 100);
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
                            'name' => 'content_view',
                            'params' => [
                                'article_name' => $article['name'],
                                'article_type' => $article['type'],
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
                        echo "イベントが正常に送信されました: {$article['name']} ({$count}回目)\n";
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
            'ニュースTOP',
            'Push通知'
        ];

        return $transitionSources;
    }

    private function getArticles(): array
    {
        $articles = [
            [
                'name' => 'FAMILY LOGOS 2024',
                'type' => '特集',
            ],
            [
                'name' => 'おうちでアウトドア＋',
                'type' => '特集',
            ],
            [
                'name' => 'Loopadd特集　by メイプルショッピング',
                'type' => '特集',
            ],
            [
                'name' => '横浜簡単中華BBQ!',
                'type' => '月刊ロゴス',
            ],
            [
                'name' => 'おかっぱとボブPRESENTS 韓国・済州島 食べ歩き旅。',
                'type' => '月刊ロゴス',
            ],
            [
                'name' => '2025 Regular THE LOGOS SHOW 新入社員が宣伝部長に挑戦！',
                'type' => '月刊ロゴス',
            ],
            [
                'name' => '#0198 春キャベツのチキンスープ',
                'type' => 'キャンプ飯',
            ],
            [
                'name' => '#0215 フライドさつまいも',
                'type' => 'キャンプ飯',
            ],
            [
                'name' => '#0213 味噌おでん',
                'type' => 'キャンプ飯',
            ],
            [
                'name' => 'No1 道具選びのコツ',
                'type' => 'まめ知識',
            ],
            [
                'name' => 'No2 キャンプ場に行く前の確認いろいろ',
                'type' => 'まめ知識',
            ],
            [
                'name' => 'No4 チューブラルグリルの機能',
                'type' => 'まめ知識',
            ],
            [
                'name' => '2024年度 一部製品の値上げ・値下げのお知らせ',
                'type' => '重要なお知らせ',
            ],
            [
                'name' => '台風10号の影響による商品の発送・お届け遅延の可能性について',
                'type' => '重要なお知らせ',
            ],
            [
                'name' => '2024年 夏季期間(お盆)の出荷スケジュール',
                'type' => '重要なお知らせ',
            ]

        ];

        return $articles;
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
