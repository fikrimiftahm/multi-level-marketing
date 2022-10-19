<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderMember extends Model
{
    use HasFactory;

    protected $table = 'leaders_members';

    protected $fillable = [
        'id',
        'leader_id',
        'member_id'
    ];

    public function leader()
    {
        return $this->belongsTo(Leader::class, 'leader_id', 'member_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }
}
