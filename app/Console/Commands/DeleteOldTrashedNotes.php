<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldTrashedNotes extends Command
{

    protected $signature = 'app:delete-old-trashed-notes';


    protected $description = 'Command description';

    public function handle()
    {
        $deletedCount = Notes::where('status', false)
            ->where('trashed_at', '<', now()->subDays(30))
            ->delete();

        $this->info("Deleted {$deletedCount} old trashed notes.");
    }
}
