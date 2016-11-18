@extends('layout.master')

@section('content')
    <div class="row well">
        <div class="col-md-3">
            <img src="{{ $values->data->header_image }}" class="img-thumbnail" style="width: 100%"/>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <h3>{{ $values->data->name }}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(!$values->data->is_free)
                        <a target="_blank" href="http://store.steampowered.com/app/{{ $values->data->steam_appid }}">
                            <span class="label label-success">
                                {{ $values->data->price_overview->currency }}
                                {{ number_format(substr($values->data->price_overview->final, 0, -2), 0, '.', ' ') }}
                            </span>
                        </a>
                        @if($values->data->price_overview->discount_percent > 0)
                            &nbsp;
                            <span class="label label-success">
                                {{ $values->data->price_overview->discount_percent }} % Off
                            </span>
                        @endif
                    @else
                        <span class="label label-success">
                            Free-to-play
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $values->data->detailed_description ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($values->achievement_avail)
        <div class="row">
            <div class="row">
                @foreach($values->achievements as $achievement)

                    <div class="col-md-1" style="height: 80px;">
                        @if($achievement->value === 1)
                            <img src="{{ $achievement->icon }}" class="img-thumbnail"/>
                        @else
                            <img src="{{ $achievement->icongray }}" class="img-thumbnail"/>
                        @endif
                    </div>
                    <div class="col-md-5" style="height: 80px;">
                        <b style="font-size: 18px">{{ $achievement->displayName }}</b><br/>
                        @if(isset($achievement->description))
                            <i class="text-muted" style="font-size: 12px;">{{ $achievement->description }}</i>
                        @endif
                    </div>

                @endforeach
            </div>
        </div>
    @endif

@endsection