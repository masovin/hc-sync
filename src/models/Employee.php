<?php

namespace App\Models;

use App\Models\Scopes\EmployeeScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Employee extends User
{
    use HasFactory;

    protected $table = 'users';

    protected static function booted(): void
    {
        static::addGlobalScope(new EmployeeScope);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_code', 'code');
    }

    public function setDefaultPassword()
    {
        $this->password = Hash::make(config('hc.default_password'));
        return $this;
    }

    /**
     * Interact with the employee's name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => strtoupper($value),
            set: fn (string $value) => strtoupper($value),
        );
    }

    /**
     * Interact with the employee's name.
     */
    protected function jabatanStrukturalOrganisasi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    /**
     * Interact with the employee's name.
     */
    protected function pltJabatanStrukturalOrganisasi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    /**
     * Interact with the employee's name.
     */
    protected function plhJabatanStrukturalOrganisasi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    /**
     * Get all position of employee
     */
    public function getPositionsAttribute()
    {
        $jabatan = [];

        if ($this->jabatan_struktural_organisasi) {
            $jabatan[] = $this->jabatan_struktural_organisasi->position_name;
        }

        if ($this->plt_jabatan_struktural_organisasi) {
            $jabatan[] = 'PLT ' . $this->plt_jabatan_struktural_organisasi->position_name;
        }

        if ($this->plh_jabatan_struktural_organisasi) {
            $jabatan[] = 'PLH ' . $this->plh_jabatan_struktural_organisasi->position_name;
        }

        return $jabatan;
    }
}