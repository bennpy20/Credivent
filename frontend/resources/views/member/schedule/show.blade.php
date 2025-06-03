@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('{{ $event['poster_link'] }}');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">{{ $event['name'] }}</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span>
                        <span class="mr-2"><a href="index.html">Jadwal <i class="ion-ios-arrow-forward"></i></a></span>
                        <span>{{ $event['name'] }} <i class="ion-ios-arrow-forward"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12 text-center heading-section">
                    <h2>Detail Event</h2>
                </div>
            </div>
            <div class="ftco-search">
                <div class="row">
                    <div class="col-md-12 nav-link-wrap">
                        <div class="nav nav-pills d-flex text-center" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            @foreach ($event_sessions as $event_session)
                                <a class="nav-link ftco-animate {{ $loop->first ? 'active' : '' }}"
                                    id="v-pills-{{ $event_session['id'] }}-tab" data-toggle="pill"
                                    href="#v-pills-{{ $event_session['id'] }}" role="tab"
                                    aria-controls="v-pills-{{ $event_session['id'] }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    Sesi {{ $event_session['session'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12 tab-wrap">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($event_sessions as $event_session)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="v-pills-{{ $event_session['id'] }}" role="tabpanel"
                                    aria-labelledby="day-{{ $event_session['id'] }}-tab">
                                    <div class="speaker-wrap ftco-animate d-flex">
                                        <div class="img speaker-img"
                                            style="background-image: url('{{ $event_session['speakers'][0]['speaker_image'] }}');">
                                        </div>
                                        <div class="text pl-md-5">
                                            <span class="time">{{ $event_session['session_start'] }} -
                                                {{ $event_session['session_end'] }}</span>
                                            <h2>{{ $event_session['title'] }}</h2>
                                            <p>{{ $event_session['description'] }}</p>
                                            <h3 class="speaker-name">&mdash; {{ $event_session['speakers'][0]['name'] }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section-parallax">
        <div class="parallax-img d-flex align-items-center">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
                        <h2>Subcribe to our Newsletter</h2>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                            live the blind texts. Separated they live in</p>
                        <div class="row d-flex justify-content-center mt-4 mb-4">
                            <div class="col-md-8">
                                <form action="#" class="subscribe-form">
                                    <div class="form-group d-flex">
                                        <input type="text" class="form-control" placeholder="Enter email address">
                                        <input type="submit" value="Subscribe" class="submit px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
