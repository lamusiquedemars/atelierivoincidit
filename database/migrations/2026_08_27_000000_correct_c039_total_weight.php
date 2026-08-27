<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * C039 was entered as 712 g in the historical catalogue.  The physical
     * total weight is 71.2 g.  Keep the legacy source aligned so a later
     * Arcus import cannot reintroduce the typo.
     */
    public function up(): void
    {
        DB::table('arcus_bows')
            ->where('code', 'c039')
            ->where('total_weight', 712)
            ->update([
                'total_weight' => 71.2,
                'updated_at' => now(),
            ]);

        // Laravel's --pretend mode is scoped to the primary connection.  Do
        // not let it write to the separate historical source connection.
        if (DB::connection()->pretending()) {
            return;
        }

        DB::connection('legacy')->table('bow')
            ->where('code', 'c039')
            ->where('total_weight', 712)
            ->update(['total_weight' => 71.2]);
    }

    public function down(): void
    {
        // Do not restore a known erroneous historical value on rollback.
    }
};
