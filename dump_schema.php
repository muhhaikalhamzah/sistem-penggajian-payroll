<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

foreach (DB::select('SHOW TABLES') as $table) {
    $name = collect((array)$table)->first();
    echo "\n--- $name ---\n";
    foreach (DB::select("DESCRIBE `$name`") as $col) {
        echo $col->Field . ' (' . $col->Type . ')' . ($col->Null == 'YES' ? ' NULL' : '') . "\n";
    }
}
