<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CremonaDelivery extends Model
{
    protected $fillable = ['contact_submission_id', 'idempotency_key', 'payload', 'status', 'attempts', 'response_status', 'last_error', 'last_attempt_at', 'sent_at'];

    protected $hidden = ['payload'];

    protected function casts(): array
    {
        return ['payload' => 'encrypted:array', 'last_attempt_at' => 'datetime', 'sent_at' => 'datetime'];
    }
}
