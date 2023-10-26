<?php

namespace App\Models;

use App\Observers\OrganizationObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'path',
        'name',
        'position_name',
        'active',
        'parent_code',
        'is_pejabat_tinggi'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_code', 'code');
    }

    /**
     * Interact with the organization's name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * Interact with the organization's position name.
     */
    protected function positionName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * Setup path organization
     * 
     * default the current id will by set by *X* char and then will be replace with actual ID on created observer
     */
    public function setParent($parent = null)
    {
        $this->path = "X";
        if ($parent) {
            $org = self::find($parent);
            $this->parent_code = $org->code;
            $this->path = $org->path . '-X';
        }

        return $this;
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
