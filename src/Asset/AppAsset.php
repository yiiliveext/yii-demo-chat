<?php

declare(strict_types=1);

namespace App\Asset;

use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bootstrap5\Assets\BootstrapAsset;

class AppAsset extends AssetBundle
{
    public ?string $basePath = '@assets';

    public ?string $baseUrl = '@assetsUrl';

    public ?string $sourcePath = '@resources/asset';

    public array $css = [
        'css/site.css',
    ];

    public array $js = [
        'js/app.js',
        'https://js.pusher.com/7.0/pusher.min.js',
        'https://code.jquery.com/jquery-3.5.1.min.js'
    ];

    public array $depends = [
        BootstrapAsset::class,
    ];
}
