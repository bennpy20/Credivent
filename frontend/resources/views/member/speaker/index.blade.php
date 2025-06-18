@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../memberast/images/member_speaker.png');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Pembicara</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('member.index') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Pembicara <i
                                class="ion-ios-arrow-forward"></i></span></p>
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
            @if ($speakers)
                <div class="row">
                    @foreach ($speakers as $speaker)
                        <div class="col-md-4 d-flex flex-column align-items-center speaker mb-4">
                            <div class="rounded overflow-hidden d-flex justify-content-center align-items-center"
                                style="width: 200px; height: 200px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                <img src="{{ $speaker['speaker_image'] }}" class="img-fluid" alt="Foto Speaker"
                                    style="width: 100%; height: 100%; object-fit: cover; border:none">
                            </div>
                            <div class="text text-center pt-3">
                                <h3>{{ $speaker['name'] ?? 'John Adams' }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" class="text-secondary mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008H14.25V9.75zM12 15.75c1.5 0 2.25-.75 2.25-.75s-.75-1.5-2.25-1.5-2.25 1.5-2.25 1.5.75.75 2.25.75zm0 6.75a9.75 9.75 0 100-19.5 9.75 9.75 0 000 19.5z" />
                    </svg>
                    <h4 class="text-muted">Data pembicara tidak ditemukan</h4>
                </div>
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
            @endif
        </div>
    </section>
@endsection
