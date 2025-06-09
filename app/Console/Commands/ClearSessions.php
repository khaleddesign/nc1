<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearSessions extends Command
{
    protected $signature = 'session:clear';
    protected $description = 'Clear all session files';

    public function handle()
    {
        $files = File::glob(storage_path('framework/sessions/*'));
        $files = array_filter($files, function ($file) {
            return basename($file) !== '.gitignore';
        });
        
        File::delete($files);
        $this->info('Sessions cleared successfully.');
    }
}
