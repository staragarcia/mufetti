<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $schema = env('DB_SCHEMA');

        // Disable all triggers, constraints, and transactional restrictions
        // so DROP SCHEMA CASCADE doesn't kill Postgres inside a transaction.
        DB::statement('SET session_replication_role = replica');

        // Inject schema name into seed
        if ($schema !== null) {
            DB::statement("SELECT set_config('app.schema', ?, false)", [$schema]);
        }

        // Load SQL file
        $path = base_path('database/mufetti-seed.sql');
        $sql = file_get_contents($path);

        // Run SQL file
        DB::unprepared($sql);

        // load inserst
        $path = base_path('database/mufetti_database/populate.sql');
        $sql = file_get_contents($path);

        DB::unprepared($sql);

        // Restore safe defaults
        DB::statement('SET session_replication_role = DEFAULT');

        $this->command?->info('Database seeded using schema: ' . ($schema ?? 'thingy (default)'));
    }
}
