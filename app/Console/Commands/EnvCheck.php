<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnvCheck extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'env:check';

    /**
     * The console command description.
     */
    protected $description = 'Check critical environment configuration and database connectivity';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Environment configuration check');

        $issues = [];

        $appKey = config('app.key');
        if (empty($appKey)) {
            $issues[] = 'APP_KEY is missing.';
        }

        $appEnv = config('app.env');
        if (empty($appEnv)) {
            $issues[] = 'APP_ENV is not set.';
        }

        $dbConnection = config('database.default');
        if (empty($dbConnection)) {
            $issues[] = 'DB_CONNECTION is not set.';
        }

        $databaseUrl = env('DATABASE_URL');
        $dbSslMode = config('database.connections.pgsql.sslmode') ?? env('DB_SSLMODE');

        // Prefer DATABASE_URL on platforms like Railway
        if (empty($databaseUrl)) {
            $this->line('DATABASE_URL: not set (falling back to individual DB_* vars)');
        } else {
            $this->line('DATABASE_URL: present');
        }

        if ($dbConnection === 'pgsql') {
            if (empty($dbSslMode)) {
                $this->line('DB_SSLMODE: not set (consider require/verify-full for public endpoints)');
            } else {
                $this->line('DB_SSLMODE: '.$dbSslMode);
            }
        }

        // Check storage symlink
        $publicStorage = public_path('storage');
        $storageTarget = storage_path('app/public');
        if (is_link($publicStorage) && readlink($publicStorage) === $storageTarget) {
            $this->line('Storage symlink: OK');
        } else {
            $issues[] = 'Storage symlink missing or incorrect (run php artisan storage:link).';
        }

        // Try a lightweight DB connection
        try {
            DB::connection()->getPdo();
            $this->line('Database connection: OK');
        } catch (\Throwable $e) {
            $issues[] = 'Database connection failed: '.$e->getMessage();
        }

        if (!empty($issues)) {
            $this->error('Issues found:');
            foreach ($issues as $issue) {
                $this->error('- '.$issue);
            }
            return self::FAILURE;
        }

        $this->info('All checks passed.');
        return self::SUCCESS;
    }
}