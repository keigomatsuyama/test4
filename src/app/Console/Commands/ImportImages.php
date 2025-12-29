<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportImages extends Command
{
    protected $signature = 'images:import';
    protected $description = 'Import public/images to storage/app/public/items';

    public function handle()
    {
        foreach (glob(public_path('images/*.{jpg,png}', GLOB_BRACE)) as $file) {
            Storage::disk('public')->put(
                'items/' . basename($file),
                file_get_contents($file)
            );
        }

        $this->info('imported');
        return Command::SUCCESS;
    }
}
