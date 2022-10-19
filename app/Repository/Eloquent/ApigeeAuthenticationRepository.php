<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\ApigeeAuthenticationRepositoryInterface;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Hash;

class ApigeeAuthenticationRepository implements ApigeeAuthenticationRepositoryInterface
{
    public function register($name, $email, $password, $role)
    {
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role
            ]);

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function signIn($email, $password)
    {
        try {
            $user = User::where('email', $email)->first();

            if ($user) {
                $validate = Hash::check($password, $user->password);

                if ($validate) {
                    if ($user->role == 'admin') {
                        session()->put('admin', true);
                    }
                    
                    session()->put('signedin', true);
                    session()->put('email', $user->email);

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function signOut()
    {
        try {
            session()->flush();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
