<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    public function leader()
    {
        return $this->hasOne(Leader::class, 'member_id', 'id');
    }

    public function leadersMembers()
    {
        return $this->hasOne(LeaderMember::class, 'member_id', 'id');
    }
}
