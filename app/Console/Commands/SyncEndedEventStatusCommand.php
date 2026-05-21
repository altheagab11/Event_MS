<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class SyncEndedEventStatusCommand extends Command
{
    protected $signature = 'events:sync-ended-status';

    protected $description = 'Mark events as done once their scheduled end time has passed';

    public function handle(): int
    {
        $updated = 0;

        Event::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereNotIn('status', ['archived', 'done']);
            })
            ->orderBy('event_id')
            ->chunkById(100, function ($events) use (&$updated): void {
                foreach ($events as $event) {
                    if ($event->syncStatusIfEnded()) {
                        $updated++;
                    }
                }
            }, 'event_id');

        $this->info("Synced ended event status. Updated: {$updated}.");

        return self::SUCCESS;
    }
}
