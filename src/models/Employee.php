<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class Employee extends User
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'profile_photo_path',
        'organization_code',
        'nip',
        'jabatan_struktural_organisasi',
        'plt_jabatan_struktural_organisasi',
        'plh_jabatan_struktural_organisasi',
        'active',
        'username',
        'employment_status'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('employee', function (Builder $builder) {
            $builder->whereNotNull('nip');
        });
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
        } else {
            $jabatan[] = "STAFF " . $this->organization->name;
        }

        if ($this->plt_jabatan_struktural_organisasi) {
            $jabatan[] = 'PLT ' . $this->plt_jabatan_struktural_organisasi->position_name;
        }

        if ($this->plh_jabatan_struktural_organisasi) {
            $jabatan[] = 'PLH ' . $this->plh_jabatan_struktural_organisasi->position_name;
        }

        return $jabatan;
    }

    public function getPositionAttribute()
    {
        if ($this->jabatan_struktural_organisasi) {
            $position = [
                'organization_code' => $this->jabatan_struktural_organisasi->code,
                'organization' => $this->jabatan_struktural_organisasi,
                'name' => $this->jabatan_struktural_organisasi->position_name
            ];
        } elseif ($this->plt_jabatan_struktural_organisasi) {
            $position = [
                'organization_code' => $this->plt_jabatan_struktural_organisasi->code,
                'organization' => $this->plt_jabatan_struktural_organisasi,
                'name' => 'PLT ' . $this->plt_jabatan_struktural_organisasi->position_name
            ];
        } elseif ($this->plh_jabatan_struktural_organisasi) {
            $position = [
                'organization_code' => $this->plh_jabatan_struktural_organisasi->code,
                'organization' => $this->plh_jabatan_struktural_organisasi,
                'name' => 'PLH ' . $this->plh_jabatan_struktural_organisasi->position_name
            ];
        } else {
            $position = [
                'organization_code' => $this->organization_code,
                'organization' => $this->organization,
                'name' => 'STAFF ' . ($this->organization->name ?? '')
            ];
        }

        return (object) $position;
    }


    public static function make(array $data)
    {
        return new self($data);
    }
}
