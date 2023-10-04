<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamWorkMembership extends Model
{
    use HasFactory;
    const TYPE_LEADER = 1;
    const TYPE_MEMBER = 2;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
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
