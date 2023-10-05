<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcSyncEvent extends Model
{
    use HasFactory;
    protected $table = "hc_sync_events";

    protected $fillable = ['hash', 'datetime', 'name', 'data'];
}
