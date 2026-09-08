<?php

namespace App\Console\Commands;

use App\Jobs\SendCremonaDelivery;
use App\Models\CremonaDelivery;
use Illuminate\Console\Command;

class RetryCremonaDeliveriesCommand extends Command
{
    protected $signature = 'cremona:retry-deliveries {--limit=50 : Maximum number of pending deliveries to retry}';

    protected $description = 'Retry pending contact deliveries to Cremona.';

    public function handle(): int
    {
        $deliveries = CremonaDelivery::query()
            ->where('status', 'pending')
            ->orderBy('last_attempt_at')
            ->limit((int) $this->option('limit'))
            ->get();

        foreach ($deliveries as $delivery) {
            SendCremonaDelivery::dispatchSync($delivery->getKey());
        }

        $this->info("{$deliveries->count()} delivery(s) retried.");

        return self::SUCCESS;
    }
}
