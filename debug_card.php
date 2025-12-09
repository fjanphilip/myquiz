<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

try {
    echo "--- START DEBUG ---\n";
    
    // 1. Create or Find User
    $user = \App\Models\User::first();
    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'Debug User',
            'email' => 'debug_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);
    }
    echo "User ID: " . $user->id . "\n";

    // 2. Create StudySet
    $set = \App\Models\StudySet::create([
        'user_id' => $user->id,
        'title' => 'Debug Set ' . uniqid(),
        'description' => 'Debug Description'
    ]);
    echo "Set ID: " . $set->id . "\n";

    // 3. Create Card
    $card = \App\Models\Card::create([
        'study_set_id' => $set->id,
        'japanese_word' => 'Debug Word',
        'japanese_reading' => 'Read',
        'meaning' => 'Mean',
        'is_mastered' => false
    ]);
    echo "Card ID: " . $card->id . "\n";
    echo "--- SUCCESS ---\n";

} catch (\Exception $e) {
    echo "--- ERROR ---\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
