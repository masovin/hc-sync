<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamWork extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'organization_code', 'name', 'active'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_code', 'code');
    }

    public function members()
    {
        return $this->hasMany(TeamWorkMembership::class, 'team_work_code', 'code');
    }

    public function teamLeaders()
    {
        return $this->members()->leader();
    }

    public function teamMembers()
    {
        return $this->members()->member();
    }

    public function getTeamLeader()
    {
        return $this->teamLeaders()->active()->first();
    }
}
