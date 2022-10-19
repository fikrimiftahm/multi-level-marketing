<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Repository\MemberRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    private MemberRepositoryInterface $memberRepositoryInterface;

    public function __construct(MemberRepositoryInterface $memberRepositoryInterface)
    {
        $this->memberRepositoryInterface = $memberRepositoryInterface;
    }

    public function index()
    {
        try {
            $type = session('type', null);
            $msg = session('message', null);
            $members = Member::get();

            return Inertia::render('Member/Index', [
                'type' => $type,
                'message' => $msg,
                'members' => $members,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message', $e]);
        }
    }

    public function registerSubmit(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'parent' => 'required',
            ]);

            $member = $this->memberRepositoryInterface->createMember($request->name);
            $leader = $this->memberRepositoryInterface->createLeader($request->parent);
            $leaderMember = $this->memberRepositoryInterface->createLeaderMember($leader->member_id, $member->id);

            if ($leaderMember) {
                return redirect()->back()->with(['type' => 'success', 'message' => 'Member registered successfully']);
            } else {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Failed to register member']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }

    public function moveIndex()
    {
        try {
            $type = session('type', null);
            $msg = session('message', null);
            $members = Member::get();
            $map = $this->memberRepositoryInterface->getLeaderMemberMap();

            return Inertia::render('Member/Move', [
                'type' => $type,
                'message' => $msg,
                'members' => $members,
                'map' => $map,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message', $e]);
        }
    }

    public function moveSubmit(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'parent' => 'required',
            ]);

            $member = $this->memberRepositoryInterface->createMember($request->name);
            $leader = $this->memberRepositoryInterface->createLeader($request->parent);
            $leaderMember = $this->memberRepositoryInterface->createLeaderMember($leader->member_id, $member->id);

            if ($leaderMember) {
                return redirect()->back()->with(['type' => 'success', 'message' => 'Leader changed successfully']);
            } else {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Failed to change leader']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }

    public function bonusIndex()
    {
        try {
            $type = session('type', null);
            $msg = session('message', null);
            $members = Member::get();

            return Inertia::render('Member/Bonus', [
                'type' => $type,
                'message' => $msg,
                'members' => $members,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message', $e]);
        }
    }

    public function bonusSubmit(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
            ]);

            $map = $this->memberRepositoryInterface->getLeaderMemberMapCount($request->name);

            $level2 = [];
            for ($i = 0; $i < count($map); $i++) {
                $map2 = $this->memberRepositoryInterface->getLeaderMemberMapCount($map[$i]->member_name);

                array_push($level2, $map2);
            }

            if ($map) {
                return response()->json([
                    'level1' => $map,
                    'level2' => $level2
                ], 200);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Not a parent'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'type' => 'error',
                    'message' => 'Failed to calculate ' . $e
                ],
                500
            );
        }
    }
}
