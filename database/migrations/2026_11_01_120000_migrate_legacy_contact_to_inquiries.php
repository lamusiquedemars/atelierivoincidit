<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maracuja\SiteCore\Contact\Models\Inquiry;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('legacy_contact_inquiry_mappings')) {
            Schema::create('legacy_contact_inquiry_mappings', function (Blueprint $table): void {
                $table->unsignedBigInteger('contact_submission_id')->primary();
                $table->foreignId('inquiry_id')->unique()->constrained('inquiries');
            });
        }

        if (Schema::hasTable('cremona_deliveries') && ! Schema::hasColumn('cremona_deliveries', 'inquiry_id')) {
            Schema::table('cremona_deliveries', function (Blueprint $table): void {
                $table->foreignId('contact_submission_id')->nullable()->change();
                $table->foreignId('inquiry_id')->nullable()->unique()->constrained('inquiries');
            });
        }

        if (! Schema::hasTable('contact_submissions')) {
            return;
        }

        DB::table('contact_submissions')->orderBy('id')->chunkById(100, function ($submissions): void {
            foreach ($submissions as $submission) {
                DB::transaction(function () use ($submission): void {
                    $mapping = DB::table('legacy_contact_inquiry_mappings')->where('contact_submission_id', $submission->id)->first();
                    if (! $mapping) {
                        $inquiry = new Inquiry;
                        $inquiry->fill([
                            'name' => $submission->name, 'email' => $submission->email,
                            'phone' => $submission->phone, 'subject' => $submission->subject,
                            'message' => $submission->message, 'source' => 'website_contact_form',
                            'read_at' => $submission->read_at ?? null, 'handled_at' => $submission->handled_at,
                            'status' => $submission->handled_at ? 'handled' : (($submission->read_at ?? null) ? 'to_handle' : 'new'),
                        ]);
                        $inquiry->created_at = $submission->created_at;
                        $inquiry->updated_at = $submission->updated_at;
                        $inquiry->save();
                        DB::table('legacy_contact_inquiry_mappings')->insert(['contact_submission_id' => $submission->id, 'inquiry_id' => $inquiry->id]);
                        $inquiryId = $inquiry->id;
                    } else {
                        $inquiryId = $mapping->inquiry_id;
                    }
                    if (Schema::hasTable('cremona_deliveries')) {
                        DB::table('cremona_deliveries')->where('contact_submission_id', $submission->id)->whereNull('inquiry_id')->update(['inquiry_id' => $inquiryId]);
                    }
                });
            }
        });
    }

    public function down(): void
    {
        // Historical data and mappings are intentionally retained.
    }
};
