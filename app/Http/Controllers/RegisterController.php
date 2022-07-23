<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function main(Request $request)
    {
        $username = strtoupper($request->input('username'));
        $password = $request->input('password');
        $cpassword = $request->input('cpassword');
        $email = $request->input('email');
            if($password != $cpassword)
            {
                $request->session()->flash('status', 'Passwords didnt match');
                return redirect("/");
            }
            $user = DB::table('account')->where('username', '=', $username)->count();
            if($user >= 1)
            {
                $request->session()->flash('status', 'Username already registered.');
                return redirect("/");
            }
            $emaildb = DB::table('account')->where('email', '=', $email)->count();
            if($emaildb >= 1)
            {
                $request->session()->flash('status', 'Email already registered.');
                return redirect("/");
            }
            if(strlen($username) <= 3)
            {
                $request->session()->flash('status', 'Your username must contain more than 3 characters.');
                return redirect("/");
            }
            if(strlen($username) >= 17)
            {
                $request->session()->flash('status', 'Your username is too long.');
                return redirect("/");
            }
            if(strlen($password) <= 3)
            {
                $request->session()->flash('status', 'Your password is too short.');
                return redirect("/");
            }
            if(strlen($password) >= 17)
            {
                $request->session()->flash('status', 'Your password is too long.');
                return redirect("/");
            }
            $encrypt = RegisterController::GetSRP6RegistrationData($username, $password);
            DB::table('account')->insert([
                'username' => $username,
                'email' => $email,
                'salt' => $encrypt[0],
                'verifier' => $encrypt[1]
            ]);
            //Success
            $request->session()->flash('success', 'Your account was successfully created!');
            return redirect("/");

    }

    private function CalculateSRP6Verifier($username, $password, $salt)
    {
        // algorithm constants
        $g = gmp_init(7);
        $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);
        
        // calculate first hash
        $h1 = sha1(strtoupper($username . ':' . $password), TRUE);
        
        // calculate second hash
        $h2 = sha1($salt.$h1, TRUE);
        
        // convert to integer (little-endian)
        $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);
        
        // g^h2 mod N
        $verifier = gmp_powm($g, $h2, $N);
        
        // convert back to a byte array (little-endian)
        $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);
        
        // pad to 32 bytes, remember that zeros go on the end in little-endian!
        $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);
        
        // done!
        return $verifier;
    }
    private function GetSRP6RegistrationData($username, $password)
    {
        // generate a random salt
        $salt = random_bytes(32);
        
        // calculate verifier using this salt
        $verifier = RegisterController::CalculateSRP6Verifier($username, $password, $salt);
        
        // done - this is what you put in the account table!
        return array($salt, $verifier);
    }
}