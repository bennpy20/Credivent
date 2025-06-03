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
                        <span class="mr-2"><a href="index.html">Registrasi <i
                                    class="ion-ios-arrow-forward"></i></a></span>
                        <span>{{ $event['name'] }} <i class="ion-ios-arrow-forward"></i></span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <h2 class="mb-4">Register Event</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 ftco-animate">
                    <div class="card shadow bg-light p-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4 text-center"><strong>Data Diri</strong></h5>

                            <div class="mb-3">
                                Nama Lengkap:
                                <p class="form-control-plaintext">{{ $user['name'] }}</p>
                            </div>
                            <div class="mb-3">
                                Email:
                                <p class="form-control-plaintext">{{ $user['email'] }}</p>
                            </div>
                            <div class="mb-3">
                                Nomor Telepon:
                                <p class="form-control-plaintext">{{ $user['phone_number'] }}</p>
                            </div>

                            <form action="{{ route('member.registration.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user['id'] }}">

                                <h5 class="card-title mt-5 mb-3 text-center"><strong>Pilih Sesi yang Diikuti</strong></h5>

                                @foreach ($event_sessions as $session)
                                    <div class="form-check mb-2 form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="session_ids[]"
                                            value="{{ $session['id'] }}" id="session{{ $session['id'] }}">
                                        <label class="form-check-label" for="session{{ $session['id'] }}">
                                            Sesi {{ $session['session'] }}: {{ $session['title'] }}
                                            ({{ $session['session_start'] }} - {{ $session['session_end'] }})
                                        </label>
                                    </div>
                                @endforeach

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary py-3 px-5">Daftar Sekarang</button>
                                </div>
                            </form>
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
