@extends('layout.master')

@section('content')
    <div class="row well">
        <div class="col-md-3">
            <img src="{{ $values->profile->avatarfull }}" class="img-thumbnail" style="width: 100%"/>
        </div>
        <div class="col-md-9">
            <table class="table table-bordered">
                <tr>
                    <td colspan="2">
                        <h3>{{ $values->profile->personaname }}</h3>
                        <a href="{{ $values->profile->profileurl }}"> {{ $values->profile->steamid }} </a>
                    </td>
                </tr>
                <tr>
                    <td class="text-left" style="width: 12em;">Community Ban</td>
                    @if($values->bans->CommunityBanned)
                        <td class="text-danger">Banned</td>
                    @else
                        <td class="text-primary">None</td>
                        @endif
                        </td>
                </tr>
                <tr>
                    <td class="text-left">VAC Ban</td>
                    @if($values->bans->VACBanned)
                        <td class="text-danger">Banned ({{ $values->bans->NumberOfVACBans }})</td>
                    @else
                        <td class="text-primary">None</td>
                        @endif
                        </td>
                </tr>
                <tr>
                    <td class="text-left">Days Since Last Ban</td>
                    @if($values->bans->DaysSinceLastBan > 0)
                        <td class="text-danger">{{ $values->bans->DaysSinceLastBan }} days</td>
                    @else
                        <td class="text-primary">{{ $values->bans->DaysSinceLastBan }} days</td>
                        @endif
                        </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <form class="form-group" name="search" action="?search" method="GET">
            <div class="col-md-4">
                    <span class="input-group">
                        <label class="input-group-addon">Game Name</label>
                        <input class="form-control" type="text" name="name"/>
                    </span>
            </div>
            <div class="col-md-2">
                <input type="submit" class="btn btn-primary" value="Search"/>
            </div>
        </form>
    </div>
    <hr/>
    <div class="row">
        <table class="table table-striped">
            <tr>
                <th colspan="2">Games</th>
                <th>Time Played</th>
            </tr>
            @foreach ($values->games as $game)
                <tr>
                    <td style="width: 10%;">
                        <a href="../profile/{{ $values->profile->steamid }}/{{ $game->appid }}" />
                            <img width="100%" class="img-thumbnail"
                                 src="http://media.steampowered.com/steamcommunity/public/images/apps/{{ $game->appid }}/{{ $game->img_logo_url }}.jpg"/>
                        </a>
                    </td>
                    <td>
                        {{ $game->name }}
                        <br/>
                        <i class="text-muted">AppID: {{ $game->appid }}</i>
                    </td>
                    <td>{{ round($game->playtime_forever/60.0, 1) }} Hours</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="row">
        <div class="col-md-3">
            <ul class="pagination">
                <li>
                    @if(isset($_GET['page']))
                        @if($_GET['page'] < 2)
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        @else
                            <a href="#" aria-label="Previouss">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        @endif
                    @else
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    @endif
                </li>
            </ul>
        </div>
        <div class="col-md-6 text-center">
            Page
            @if(isset($_GET['page']))
                {{ $_GET['page'] }}
            @else
                1
            @endif
            of {{ ceil($values->game_count / 10.0) }}
        </div>
        <div class="col-md-3">
            PREV
        </div>
    </div>

@endsection