<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'member_id'
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function leadersMembers()
    {
        return $this->hasMany(LeaderMember::class, 'leader_id', 'member_id');
    }
}
