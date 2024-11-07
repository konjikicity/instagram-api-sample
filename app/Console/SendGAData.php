<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendGAData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-ga-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GA4イベントテスト送信用コマンド';

    /**
     * ・カタログダウンロード
     * ・コンテンツ閲覧
     * ・ピックアップ閲覧
     * ・製品閲覧
     * ・チェックイン
     * ・店舗フォロー
     *
     * 上記の実績をGAに送信する
     *
     */
    public function handle(): void
    {
        $this->call('app:send-ga-catalog-download');
        $this->call('app:send-ga-countent-view');
        $this->call('app:send-ga-pickup-view');
        $this->call('app:send-ga-product-view');
        $this->call('app:send-ga-shop-check-in');
        $this->call('app:send-ga-shop-follow');

        echo 'GAへのデータが送信完了しました。';
    }
}
