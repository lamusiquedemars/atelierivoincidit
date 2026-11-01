<?php

namespace App\Console\Commands;

use App\Support\SitePolicyReport;
use Illuminate\Console\Command;

class MaracujaPolicyReportCommand extends Command
{
    protected $signature = 'maracuja:policy-report {--strict : Échoue si le manifeste et le runtime divergent}';

    protected $description = 'Compare le manifeste de capacités Maracuja Control à l’état runtime local.';

    public function handle(SitePolicyReport $report): int
    {
        $result = $report->inspect();

        $this->info("Politique Maracuja Control ({$result['mode']})");
        $this->line("Manifeste : {$result['path']}");

        foreach ($result['errors'] as $error) {
            $this->error($error);
        }

        $this->table(['Capacité', 'Politique', 'Installée', 'Runtime', 'État'], array_map(fn (array $row): array => [
            $row['capability'], $row['policy'] ? 'oui' : 'non', $row['installed'] ? 'oui' : 'non', $row['runtime'] ? 'oui' : 'non', strtoupper($row['status']),
        ], $result['rows']));

        $hasDrift = $result['errors'] !== [] || collect($result['rows'])->contains(fn (array $row): bool => $row['status'] === 'drift');

        return $hasDrift && $this->option('strict') ? self::FAILURE : self::SUCCESS;
    }
}
