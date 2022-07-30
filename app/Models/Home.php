<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    public function getAccounts()
    {
        return DB::table('account')->count();
    }
    public function getBanned()
    {
        return DB::table('account_banned')->where('active', 1)->count();
    }
    public function getUptime()
    {
        return DB::table('uptime')->orderby('starttime', 'desc')->first();
    }
    public function getPlyrs()
    {
        return DB::connection('char')->table('characters')->where('online', '1')->count();
    }
}
