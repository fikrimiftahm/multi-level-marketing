<?php

namespace App\Repository;

interface ApigeeAuthenticationRepositoryInterface
{
    public function register($name, $email, $password, $role);
    public function signIn($email, $password);
    public function signOut();
}