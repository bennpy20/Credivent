@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight"
        style="background-image: url('../memberast/images/member_schedule.png');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Jadwal Event</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('member.index') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Jadwal <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            @if (!empty($events))
                <div class="row d-flex">
                    @foreach ($events as $event)
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="blog-entry justify-content-end">
                                <a href="#" class="block-20"
                                    style="background-image: url('{{ $event['poster_link'] }}');">
                                </a>
                                <div class="text p-4 float-right d-block">
                                    <div class="d-flex align-items-center pt-2 mb-4">
                                        <div class="event-date-display">{{ $event['date_display'] }}</div>
                                    </div>
                                    <h3 class="heading mt-2"><strong>{{ $event['name'] }}</strong></h3>
                                    <p>Lokasi: {{ $event['location'] }}</p>
                                    {{-- <p>Kapasitas Peserta: {{ $event['max_participants'] }}</p> --}}
                                    <p>Kapasitas Tersedia: {{ $event['available_capacity'] }}</p>
                                    <p>Biaya Tiket: Rp
                                        {{ number_format($event['transaction_fee'], 0, ',', '.') }}</p>
                                    <p>Status Event: {{ $event['event_status_text'] }}</p>
                                    <a href="{{ route('member.schedule.show', $event['id']) }}"
                                        class="btn btn-primary mt-2">
                                        Detail event
                                    </a>
                                    @if (session()->has('user'))
                                        @if (session('user.role') == 2)
                                            @if ($event['available_capacity'] > 0)
                                                {{-- Member: tombol registrasi aktif jika masih ada kuota --}}
                                                <a href="{{ route('member.registration.show', $event['id']) }}"
                                                    class="btn btn-primary mt-2">
                                                    Registrasi
                                                </a>
                                            @else
                                                {{-- Member: tombol disabled jika penuh --}}
                                                <button class="btn btn-secondary mt-2" disabled>
                                                    Kuota Penuh
                                                </button>
                                            @endif
                                        @elseif (session('user.role') == 1 || session('user.role') == 3 || session('user.role') == 4)
                                            {{-- Panitia / Tim Keuangan: munculkan alert --}}
                                            <button class="btn btn-secondary mt-2"
                                                onclick="Swal.fire('Oops!', 'Anda bukan member, tidak bisa registrasi event', 'warning')">
                                                Registrasi
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary mt-2">
                                                Registrasi
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary mt-2">
                                            Registrasi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- KALO KOSONG -->
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" class="text-secondary mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008H14.25V9.75zM12 15.75c1.5 0 2.25-.75 2.25-.75s-.75-1.5-2.25-1.5-2.25 1.5-2.25 1.5.75.75 2.25.75zm0 6.75a9.75 9.75 0 100-19.5 9.75 9.75 0 000 19.5z" />
                    </svg>
                    <h4 class="text-muted">Data tidak ditemukan</h4>
                    <p class="text-secondary">Tidak ada event yang sedang berlangsung atau mendatang..</p>
                </div>
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
            @endif
            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                        <ul>
                            <li><a href="#">&lt;</a></li>
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">&gt;</a></li>
                        </ul>
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
