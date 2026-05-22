<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

Mail::raw('Local test mail from Laravel', function ($m) {
    $m->to('test@local.test')->subject('Local Test');
});

echo "Mail send attempted\n";
