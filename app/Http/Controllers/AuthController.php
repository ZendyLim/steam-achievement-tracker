<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{

    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    private function checkLogin(Request $request)
    {
        if ($request->session()->has('STEAM_ID'))
            return true;
        return false;
    }

    public function index(Request $request)
    {
        if($this->checkLogin($request))
            return view('profile');
        return view('welcome');
    }

    public function login(Request $request)
    {
        if($this->checkLogin($request))
            return view('profile');

        if ($this->steam->validate())
        {
            $info = $this->steam->getUserInfo();
            if (!is_null($info))
            {
                $STEAM_ID = $this->steam->getSteamID();
                $API_KEY = env('STEAM_API_KEY', '');
                $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$API_KEY&steamids=$STEAM_ID";
                $json = file_get_contents($url);
                $player = json_decode($json)->response->players[0];

                $request->session()->put('STEAM_ID', $player->steamid);
                $request->session()->put('STEAM_NAME', $player->personaname);
                $request->session()->put('AVATAR_S', $player->avatar);
                $request->session()->put('AVATAR_M', $player->avatarmedium);
                $request->session()->put('AVATAR_L', $player->avatarfull);

                return redirect('/');
            }
        }

        return $this->steam->redirect();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/');
    }
}
