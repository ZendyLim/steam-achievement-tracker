@extends('layout.master')

@section('content')
    <div class="row well">
        <div class="col-md-3">
            <img src="{{ $values->profile->avatarfull }}" class="img-thumbnail" style="width: 100%"/>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{ $values->profile->personaname }}</h3>
                    <a href="{{ $values->profile->profileurl }}"> {{ $values->profile->steamid }} </a>
                </div>
            </div>
            <br/>
            <br/>
            <div class="row">
                <div class="col-md-2">
                    <b>Community Ban</b>
                </div>
                @if($values->bans->CommunityBanned)
                    <div class="col-md-2 text-danger">Banned</div>
                @else
                    <div class="col-md-2 text-primary">None</div>
                @endif
            </div>
            <br/>
            <div class="row">
                <div class="col-md-2">
                    <b>VAC Ban</b>
                </div>
                @if($values->bans->VACBanned)
                    <div class="col-md-2 text-danger">Banned ({{ $values->bans->NumberOfVACBans }})</div>
                @else
                    <div class="col-md-2 text-primary">None</div>
                @endif
            </div>
            <br/>
            <div class="row">
                <div class="col-md-2">
                    <b>VAC Ban</b>
                </div>
                @if($values->bans->VACBanned)
                    <div class="col-md-2 text-danger">{{ $values->bans->DaysSinceLastBan }} days</div>
                @else
                    <div class="col-md-2 text-primary">{{ $values->bans->DaysSinceLastBan }} days</div>
                @endif
            </div>
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
    @if($values->game_count < 1)
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>No result</h2>
            </div>
        </div>
    @else
        <div class="row">
            <table class="table table-striped">
                <tr>
                    <th colspan="2">Games</th>
                    <th>Time Played</th>
                </tr>
                @foreach ($values->games as $game)
                    <tr>
                        <td style="width: 10%;">
                            <a href="../profile/{{ $values->profile->steamid }}/{{ $game->appid }}"/>
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
                        @if($values->page < 2)
                            <a href="?page={{ 1 }}{{ $values->link }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Prev</span>
                            </a>
                        @else
                            <a href="?page={{ $values->page - 1 }}{{ $values->link }}" aria-label="Previouss">
                                <span aria-hidden="true">&laquo; Prev</span>
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="col-md-6 text-center">
                <ul class="pagination">
                    <li>
                        Page
                        @if(isset($_GET['page']))
                            {{ $_GET['page'] }}
                        @else
                            1
                        @endif
                        of {{ ceil($values->game_count / 10.0) }}
                    </li>
                </ul>
            </div>
            <div class="col-md-3" align="right">
                <ul class="pagination">
                    <li>
                        @if($values->page >= ceil($values->game_count / 10.0))
                            <a href="?page={{ ceil($values->game_count / 10.0) }}{{ $values->link }}"
                               aria-label="Previous">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        @else
                            <a href="?page={{ $values->page + 1 }}{{ $values->link }}" aria-label="Previouss">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    @endif

@endsection