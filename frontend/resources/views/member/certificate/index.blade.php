@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../memberast/images/bg_2.jpg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Lihat Sertifikat</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Lihat Sertifikat <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="page-header mb-4 text-center">
                <h2 class="fw-bold">Daftar Sertifikat</h2>
            </div>

            @if(count($certificates) === 0)
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">

                <div class="text-center py-5">
                    <!-- Ikon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" class="text-secondary mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008H14.25V9.75zM12 15.75c1.5 0 2.25-.75 2.25-.75s-.75-1.5-2.25-1.5-2.25 1.5-2.25 1.5.75.75 2.25.75zm0 6.75a9.75 9.75 0 100-19.5 9.75 9.75 0 000 19.5z" />
                    </svg>

                    <h4 class="text-muted">Belum ada sertifikat yang tersedia</h4>
                    <p class="text-secondary">Silakan tambahkan data event terlebih dahulu</p>
                </div>

                <!-- Garis pemisah bawah -->
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
            @else
                <div class="row">
                    @foreach ($certificates as $certificate)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $certificate['event']['name'] }}</h5>
                                    <p class="card-text mb-1"><strong>Sesi {{ $certificate['session']['session'] }}:</strong> {{ $certificate['session']['title'] }}
                                    </p>
                                    <p class="card-text text-muted mb-3"><strong>Waktu:</strong>
                                        {{ $certificate['date_display'] }}</p>
                                    <a href="{{ $certificate['certificate_link'] }}" class="btn btn-outline-primary mt-auto"
                                        target="_blank">
                                        <i class="fas fa-file-download"></i> Unduh Sertifikat
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
