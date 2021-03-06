<?php

declare(strict_types=1);

use App\Timer;
use Yiisoft\Composer\Config\Merger\Modifier\ReverseBlockMerge;
use Yiisoft\Yii\Console\Event\ApplicationStartup;

return [
    ApplicationStartup::class => [
        static fn (Timer $timer) => $timer->start('overall'),
    ],
    ReverseBlockMerge::class => new ReverseBlockMerge(),
];
