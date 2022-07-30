<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    public function Main($username, $password, $cpassword, $email)
    {
        if($password != $cpassword)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Passwords didnt match',
            ];
        }
        $user = DB::table('account')->where('username', '=', $username)->count();
        if($user >= 1)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Username already registered.',
            ];
        }
        $emaildb = DB::table('account')->where('email', '=', $email)->count();
        if($emaildb >= 1)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Email already registered.',
            ];
        }
        if(strlen($username) <= 3)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Your username must contain more than 3 characters.',
            ];
        }
        if(strlen($username) >= 17)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Your username is too long.',
            ];
        }
        if(strlen($password) <= 3)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Your password is too short.',
            ];
        }
        if(strlen($password) >= 17)
        {
            return [
                $status = 'fail',
                $sessmsg = 'Your password is too long.',
            ];
        }
        
        $encrypt = register::GetSRP6RegistrationData($username, $password);
        DB::table('account')->insert([
            'username' => $username,
            'email' => $email,
            'salt' => $encrypt[0],
            'verifier' => $encrypt[1]
        ]);

        return [
            $status = "success",
            $sessmsg = 'Your account was successfully created!',
        ];
    }

    private function CalculateSRP6Verifier($username, $password, $salt)
    {
        $g = gmp_init(7);
        $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);
        $h1 = sha1(strtoupper($username . ':' . $password), TRUE);
        $h2 = sha1($salt.$h1, TRUE);
        $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);
        $verifier = gmp_powm($g, $h2, $N);
        $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);
        $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);
        return $verifier;
    }
    private function GetSRP6RegistrationData($username, $password)
    {
        $salt = random_bytes(32);
        $verifier = register::CalculateSRP6Verifier($username, $password, $salt);
        return array($salt, $verifier);
    }
}
