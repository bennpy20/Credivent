@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <div class="hero-wrap js-fullheight" style="background-image: url('../memberast/images/bg_1.jpg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
                data-scrollax-parent="true">
                <div class="col-xl-10 ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                    <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"> Workshop
                        <br><span>PWL 2025</span>
                    </h1>
                    <p class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">25 Juni 2025 Pk
                        13.00-15.00.
                        Bandung, Indonesia</p>
                    <div id="timer" class="d-flex mb-3">
                        <div class="time" id="days"></div>
                        <div class="time pl-4" id="hours"></div>
                        <div class="time pl-4" id="minutes"></div>
                        <div class="time pl-4" id="seconds"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section services-section bg-light">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services d-block">
                        <div class="icon"><span class="flaticon-placeholder"></span></div>
                        <div class="media-body">
                            <h3 class="heading mb-3">Jadwal Acara</h3>
                            <p>Credivent mempermudah mahasiswa, organisasi, dan unit kegiatan kampus dalam mempublikasikan
                                serta mengelola jadwal acara secara terpusat. Semua pihak dapat dengan mudah mengetahui
                                kegiatan terbaru dan mengatur waktu mereka untuk berpartisipasi tanpa ketinggalan informasi
                                penting.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services d-block">
                        <div class="icon"><span class="flaticon-world"></span></div>
                        <div class="media-body">
                            <h3 class="heading mb-3">Koneksi Mahasiswa</h3>
                            <p>Credivent membuka ruang kolaborasi, memperluas jejaring sosial dan profesional, serta
                                mendorong terciptanya komunitas dinamis yang aktif dalam berbagai bidang kegiatan di
                                lingkungan universitas. Mahasiswa dari berbagai jurusan, fakultas, hingga kampus berbeda
                                dapat saling terhubung dan berinteraksi melalui kegiatan bersama.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-counter img" id="section-counter">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-6 d-flex">
                    <div class="img d-flex align-self-stretch" style="background-image:url(../memberast/images/about.jpg);">
                    </div>
                </div>
                <div class="col-md-6 pl-md-5 py-5">
                    <div class="row justify-content-start pb-3">
                        <div class="col-md-12 heading-section ftco-animate">
                            <h2 class="mb-4"><span>Tentang</span> Credivent</h2>
                            <p>Credivent adalah sebuah platform berbasis teknologi yang dirancang khusus untuk
                                mempermudah pengelolaan acara-acara di lingkungan kampus. Dengan Credivent, seluruh
                                proses mulai dari perencanaan dan organisasi acara, hingga pengelolaan partisipasi
                                peserta, dapat dilakukan dengan cara yang lebih efisien dan praktis. Semua fitur yang
                                dibutuhkan untuk menyelenggarakan acara, baik itu seminar, workshop, ataupun kegiatan
                                lainnya, tersedia dalam satu platform yang terintegrasi dan mudah digunakan. Dengan
                                demikian, seluruh aspek pengelolaan acara menjadi lebih terpusat, meminimalisir
                                kerumitan, dan memberikan kemudahan bagi penyelenggara serta peserta.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center py-4 bg-light mb-4">
                                <div class="text">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="flaticon-guest"></span>
                                    </div>
                                    <strong class="number" data-number="3000">0</strong>
                                    <span>Peserta</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center py-4 bg-light mb-4">
                                <div class="text">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="flaticon-idea"></span>
                                    </div>
                                    <strong class="number" data-number="250">0</strong>
                                    <span>Seminar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <h2 class="mb-4"><span>Daftar</span> Pembicara</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="carousel-testimony owl-carousel">
                        @foreach ($speakers as $speaker)
                            <div class="item">
                                <div class="speaker">
                                    <img src="{{ $speaker['speaker_image'] ?? 'default.jpg' }}"
                                        class="img-fluid rounded-circle" alt="Speaker Photo"
                                        style="width: 200px; height: 200px; object-fit: cover; object-position: center;
                                        margin: 0 auto; display: block;">
                                    <div class="text text-center py-3">
                                        <h3>{{ $speaker['name'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <h2 class="mb-4">Event <span> yang Diadakan</span></h2>
                </div>
            </div>
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
        </div>
    </section>
@endsection
