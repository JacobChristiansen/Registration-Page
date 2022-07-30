<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Home;

class HomeController extends Controller
{
    public function main()
    {
        $home = new Home();

        $status = false;
        if(env('AZ_STATUS_SWITCH', false))
        {
            if(is_resource(@fsockopen(env('AZ_SERVER_IP'),env('AZ_SERVER_PORT'), $errno, $errstr, 3)))
            {
                $status = true;
                fclose(@fsockopen(env('AZ_SERVER_IP'),env('AZ_SERVER_PORT')));
            }
        }
        $vars = [
            'status' => $status,
            'onplyrs' => $home->getPlyrs(),
            'regacc' => $home->getAccounts(),
            'banacc' => $home->getBanned(),
            'uptime' => $home->getUptime(),
        ];
        return view('home', $vars);

    }
}