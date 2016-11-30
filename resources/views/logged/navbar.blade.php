<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">GABEN LORD TRACKER</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" id="loginSteamButton" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ session()->get('STEAM_AVATAR') }}" />
                        {{ session()->get('STEAM_NAME') }}
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu" style="margin-top: 4px">
                        <li><a href="../profile/{{ session()->get('STEAM_ID') }}">Profile</a></li>
                        <li><a href="../logout">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>