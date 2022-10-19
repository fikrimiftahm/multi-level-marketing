<?php

namespace App\Http\Controllers;

use App\Repository\ApigeeAuthenticationRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthenticationController extends Controller
{
    private ApigeeAuthenticationRepositoryInterface $apigeeAuthenticationRepositoryInterface;

    public function __construct(ApigeeAuthenticationRepositoryInterface $apigeeAuthenticationRepositoryInterface)
    {
        $this->apigeeAuthenticationRepositoryInterface = $apigeeAuthenticationRepositoryInterface;
    }
    
    public function index()
    {
        try {
            if (session('signedin', false)) {
                return redirect()->route('dashboard.index');
            } else {
                $type = session('type', null);
                $msg = session('message', null);

                return Inertia::render('Authentication/Index', [
                    'type' => $type,
                    'message' => $msg
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }

    public function registerIndex()
    {
        try {
            if (session('signedin', false)) {
                return redirect()->route('dashboard.index');
            } else {
                $type = session('type', null);
                $msg = session('message', null);

                return Inertia::render('Authentication/Register', [
                    'type' => $type,
                    'message' => $msg
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }

    public function registerSubmit(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);

            $register = $this->apigeeAuthenticationRepositoryInterface->register($request->name, $request->email, $request->password, 'admin');

            if ($register) {
                return redirect()->route('auth.index')->with(['type' => 'success', 'message' => 'Account successfully registered']);    
            } else {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Failed to register account']);    
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }

    public function signIn(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',
            ]);

            $signIn = $this->apigeeAuthenticationRepositoryInterface->signIn($request->email, $request->password);

            if ($signIn) {
                return redirect()->route('dashboard.index');    
            } else {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Invalid credential']);    
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }

    public function signOut()
    {
        try {
            $signOut = $this->apigeeAuthenticationRepositoryInterface->signOut();

            if ($signOut) {
                return redirect()->route('auth.index');    
            } else {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Something is wrong']);    
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['type' => 'error', 'message' => $e]);
        }
    }
}
