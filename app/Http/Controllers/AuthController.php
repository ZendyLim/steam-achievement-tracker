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
            return redirect()->route('profile', ['id' => $request->session()->get('STEAM_ID')]);
        return view('welcome');
    }

    public function login(Request $request)
    {
        if($this->checkLogin($request))
            return redirect()->route('profile', ['id' => $request->session()->get('STEAM_ID')]);

        if ($this->steam->validate())
        {
            $info = $this->steam->getUserInfo();
            if (!is_null($info))
            {
                $STEAM_ID = $this->steam->getSteamID();
                $API_KEY = env('STEAM_API_KEY', '');

                $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$API_KEY&steamids=$STEAM_ID";
                $json = file_get_contents($url);
                $players = json_decode($json)->response->players;
                $player = $players[0];

                $request->session()->put('STEAM_ID', $STEAM_ID);
                $request->session()->put('STEAM_NAME', $player->personaname);
                $request->session()->put('STEAM_AVATAR', $player->avatar);

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
