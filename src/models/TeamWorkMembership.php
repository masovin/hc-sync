<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamWorkMembership extends Model
{
    use HasFactory;
    const TYPE_LEADER = 1;
    const TYPE_MEMBER = 2;

    protected $fillable = ['code', 'team_work_code', 'nip', 'employee', 'type', 'active', 'activated_at', 'deactivated_at'];

    protected function employee(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Employee::make((array)json_decode($value)),
            set: fn ($value) => json_encode($value),
        );
    }
    public function scopeLeader($query)
    {
        return $query->where('type', self::TYPE_LEADER);
    }

    public function scopeMember($query)
    {
        return $query->where('type', self::TYPE_MEMBER);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
