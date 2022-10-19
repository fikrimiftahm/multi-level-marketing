<?php

namespace App\Repository\Eloquent;

use App\Models\Leader;
use App\Models\LeaderMember;
use App\Models\Member;
use App\Models\User;
use App\Repository\MemberRepositoryInterface;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberRepository implements MemberRepositoryInterface
{
    public function getMember($name)
    {
        try {
            $member = Member::where('name', $name)->first();

            return $member;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createMember($name)
    {
        try {
            $member = Member::firstOrCreate([
                'name' => $name,
            ]);

            return $member;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createLeader($memberId)
    {
        try {
            $leader = Leader::firstOrCreate([
                'member_id' => $memberId,
            ]);

            return $leader;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createLeaderMember($leaderId, $memberId)
    {
        try {
            $leaderMember = LeaderMember::updateOrCreate(
                [
                    'member_id' => $memberId,
                ],
                [
                    'leader_id' => $leaderId,
                    'member_id' => $memberId,
                ]
            );

            return $leaderMember;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLeaderMemberMap()
    {
        try {
            $query = '
            with members as (
                select m.id member_id, m.name member_name, lm.leader_id 
                from practice.members m
                left join practice.leaders_members lm 
                on m.id = lm.member_id
                left join practice.leaders l 
                on m.id = l.member_id 
            ), mlm as (
                select m.*, me.name leader_name
                from members m
                left join practice.members me 
                on m.leader_id = me.id
                order by 1 asc, 3 asc
            )
            select *
            from mlm;';
            
            $map = DB::select(DB::raw($query));

            return $map;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLeaderMemberMapCount($name)
    {
        try {
            $query = "
            with leaders as (
                select m.id leader_id, m.name leader_name, lm.member_id 
                from practice.leaders l
                left join practice.leaders_members lm 
                on l.member_id = lm.leader_id
                left join practice.members m
                on m.id = lm.leader_id 
                where m.name = '" . $name . "'
            ), mlm as (
                select l.*, me.name member_name
                from leaders l
                left join practice.members me 
                on l.member_id = me.id
                order by 1 asc, 3 asc
            ), smr as (
                select leader_name, member_name, count(member_name) total
                from mlm
                group by 1, 2
                order by 1 asc, 2 asc
            )
            select *
            from smr;";
            
            $map = DB::select(DB::raw($query));

            return $map;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
