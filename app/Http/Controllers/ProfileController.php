<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private function sortByName($a, $b)
    {
        if ($a->name > $b->name)
            return 1;
        else if ($a->name < $b->name)
            return -1;
        else
            return 0;
    }

    private function checkLogin(Request $request)
    {
        if ($request->session()->has('STEAM_ID'))
            return true;
        return false;
    }

    public function index(Request $request, $id)
    {
        if (!$this->checkLogin($request))
            return redirect('/');

        $values = new \stdClass();
        $STEAM_ID = $id;
        $API_KEY = env('STEAM_API_KEY', '');

        /* GET PLAYER DATA */

        $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$API_KEY&steamids=$STEAM_ID";
        $json = file_get_contents($url);
        $players = json_decode($json)->response->players;

        if (sizeof($players) < 1)
            return redirect()->route('profile', ['id' => $request->session()->get('STEAM_ID')]);

        $player = $players[0];
        $values->profile = $player;

        /* GET BAN DATA */

        $url = "http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=$API_KEY&steamids=$STEAM_ID";
        $json = file_get_contents($url);
        $players = json_decode($json)->players;
        $player = $players[0];
        $values->bans = $player;

        /* GET GAME COUNT */

        $url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=$API_KEY&steamid=$STEAM_ID&include_appinfo=1&include_played_free_games=1";
        $json = file_get_contents($url);
        $responses = json_decode($json);

        usort($responses->response->games, array($this, "sortByName"));

        /* IF SEARCH IS INVOKED */

        $total_playtime = 0;

        foreach ($responses->response->games as $game)
            $total_playtime += $game->playtime_forever;

        $values->total_playtime = $total_playtime;

        if (isset($request->name))
        {
            $games = array();

            foreach ($responses->response->games as $game)
            {
                if (stripos($game->name, $_GET['name']) !== false)
                    array_push($games, $game);
            }

            $values->games = $games;
            $values->game_count = sizeof($games);
        }
        else
        {
            $values->games = $responses->response->games;
            $values->game_count = $responses->response->game_count;
        }

        /* PAGINATION */

        if (!isset($_GET['page']))
            $page = 1;
        else
            $page = $_GET['page'];

        $index = 0;
        $start_index = ($page - 1) * 10;
        $finish_index = $start_index + 10;
        $games = array();

        foreach ($values->games as $game)
        {
            if ($index >= $start_index && $index <= $finish_index)
                array_push($games, $game);
            if ($index >= $finish_index)
                break;
            $index++;
        }

        $values->games = $games;
        $values->page = $page;

        $link = '';

        if (isset($_GET['name']))
        {
            $link = $link . '&name=' . $_GET['name'];
        }

        $values->link = $link;

        /* END */

        return view('profile')->with('values', $values);
    }

    private function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    public function details(Request $request, $id, $appid)
    {
        if (!$this->checkLogin($request))
            return redirect('/');

        $values = new \stdClass();
        $STEAM_ID = $id;
        $APP_ID = $appid;
        $API_KEY = env('STEAM_API_KEY', '');

        /* GET GAME DETAILS */


        $url = "http://store.steampowered.com/api/appdetails?appids=$APP_ID&cc=ID";
        $json = file_get_contents($url);

        $data = json_decode($json);

        if ($data->$APP_ID->success)
            $data = $data->$APP_ID->data;
        else
            return redirect('/');

        $values->data = $data;

        /* GET ACHIEVEMENT DATA */

        $url = "http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=$APP_ID&key=$API_KEY&steamid=$STEAM_ID";

        if ($this->get_http_response_code($url) != "200")
            $values->achievement_avail = false;
        else
        {
            $json = file_get_contents($url);
            $achievements = json_decode($json);


            if (isset($achievements->playerstats->achievements))
            {
                $url = "http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v0002/?key=$API_KEY&appid=$APP_ID&l=english";
                $json = file_get_contents($url);
                $achievement_detail = json_decode($json)->game->availableGameStats->achievements;

                $index = 0;
                $result = array();

                foreach ($achievement_detail as $achievement)
                {
                    $achievement->value = $achievements->playerstats->achievements[$index++]->achieved;
                    array_push($result, $achievement);
                }

                $values->achievement_avail = true;
                $values->achievements = $achievement_detail;
            }
            else
                $values->achievement_avail = false;
        }
        //dd($values);

        return view('details')->with('values', $values);
    }

    public
    function redirectProfile(Request $request)
    {
        return redirect()->route('profile', ['id' => $request->session()->get('STEAM_ID')]);
    }
}
