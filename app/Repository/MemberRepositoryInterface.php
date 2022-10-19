<?php

namespace App\Repository;

interface MemberRepositoryInterface
{
    public function getMember($name);
    public function createMember($name);
    public function createLeader($memberId);
    public function createLeaderMember($leaderId, $memberId);
    public function getLeaderMemberMap();
    public function getLeaderMemberMapCount($name);
}