<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = DB::select('SHOW TABLES');
$tableNames = array_map(function($t) { return array_values((array)$t)[0]; }, $tables);

$schemaInfo = [];

foreach ($tableNames as $table) {
    if (in_array($table, ['migrations', 'failed_jobs', 'password_reset_tokens', 'personal_access_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches'])) continue;

    $columns = DB::select("SHOW COLUMNS FROM `$table`");
    $keys = DB::select("SHOW INDEX FROM `$table`");
    $fks = DB::select("
        SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table' AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    $rowCount = DB::table($table)->count();
    
    $schemaInfo[$table] = [
        'count' => $rowCount,
        'columns' => $columns,
        'keys' => $keys,
        'foreign_keys' => $fks
    ];
}

file_put_contents('schema_audit.json', json_encode($schemaInfo, JSON_PRETTY_PRINT));
echo "DONE";
