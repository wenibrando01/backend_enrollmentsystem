<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

$email = 'admin@example.com';
$password = 'secret123';

$u = User::where('email', $email)->first();
if (! $u) {
    echo "NOTFOUND\n";
} else {
    echo "FOUND\n";
    echo ($u->is_admin ? "IS_ADMIN\n" : "NOT_ADMIN\n");
    echo (Hash::check($password, $u->password) ? "PASSWORD_OK\n" : "PASSWORD_BAD\n");
}

echo (Schema::hasColumn('users', 'is_admin') ? "HAS_COL\n" : "NO_COL\n");
