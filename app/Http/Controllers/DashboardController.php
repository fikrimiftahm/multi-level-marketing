<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $type = session('type', null);
            $msg = session('message', null);

            return Inertia::render('Dashboard/Index', [
                'type' => $type,
                'message' => $msg
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message', $e]);
        }
    }
}
