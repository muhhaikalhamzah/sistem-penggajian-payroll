<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Force Superadmin login
$user = \App\Models\User::where('role', 'Superadmin')->first();
Auth::login($user);

// Create a Request
$payslip = \App\Models\Payslip::first();
if (!$payslip) {
    echo "No payslip found.\n";
    exit;
}

$request = Illuminate\Http\Request::create('/payslips/' . $payslip->id . '/print', 'GET');
$response = $app->handle($request);

echo "Status Code: " . $response->getStatusCode() . "\n";
$content = $response->getContent();
if ($response->getStatusCode() != 200) {
    echo "Content: " . substr(strip_tags($content), 0, 500) . "\n";
} else {
    echo "Page rendered successfully.\n";
}
