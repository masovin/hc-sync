<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HcSyncConfig extends Model
{
    use HasFactory;

    protected $table = "hc_sync_configs";

    protected $fillable = ['conf_key', 'conf_value'];

    public static function isLocked(): bool
    {
        $conf = self::where('conf_key', 'lock')->first();
        return $conf->conf_value ?? false;
    }
}
