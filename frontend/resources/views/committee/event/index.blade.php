@extends('components.comp_two.layout')

@section('title')
    Committee
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight"
        style="background-image: url('../memberast/images/committee_manage_event.png');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Kelola Event</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('member.index') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Kelola Event <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="d-flex justify-content-end mb-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreateEvent">
                    Tambah Event
                </button>

                @include('committee.event.create')
            </div>

            @if (!empty($events))
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
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
                                    <p>Kapasitas Peserta:
                                        {{ number_format($event['max_participants'], 0, ',', '.') }}</p>
                                    <p>Biaya Tiket: Rp
                                        {{ number_format($event['transaction_fee'], 0, ',', '.') }}</p>
                                    <p>Status Event: {{ $event['event_status_text'] }}</p>
                                    <h4 class="heading mt-2">Kelola Event</h4>
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#modalUpdateEvent{{ $event['id'] }}">
                                        Edit Event
                                    </button>
                                    <form id="delete-form-{{ $event['id'] }}" style="display: inline"
                                        action="{{ route('committee.event.destroy', $event['id']) }}" method="POST"
                                        onclick="confirmDelete('{{ $event['id'] }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete('{{ $event['id'] }}')">
                                            Hapus Event
                                        </button>
                                    </form>
                                </div>

                                <div class="text p-4 float-right d-block">
                                    <h4 class="heading mt-2">Kelola Sesi</h4>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modalTambahSesi{{ $event['id'] }}">
                                        Tambah Sesi
                                    </button>
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#modalEditSesi{{ $event['id'] }}">
                                        Edit Sesi
                                    </button>
                                </div>
                            </div>
                        </div>
                        @include('committee.event.edit', ['event' => $event])

                        @include('committee.session.create')
                        @include('committee.session.edit')
                    @endforeach
                </div>
            @else
                <!-- KALO KOSONG -->
                <!-- Garis pemisah atas -->
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">

                <div class="text-center py-5">
                    <!-- Ikon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" class="text-secondary mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008H14.25V9.75zM12 15.75c1.5 0 2.25-.75 2.25-.75s-.75-1.5-2.25-1.5-2.25 1.5-2.25 1.5.75.75 2.25.75zm0 6.75a9.75 9.75 0 100-19.5 9.75 9.75 0 000 19.5z" />
                    </svg>

                    <h4 class="text-muted">Data tidak ditemukan</h4>
                    <p class="text-secondary">Silakan tambahkan data event terlebih dahulu</p>
                </div>

                <!-- Garis pemisah bawah -->
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
            @endif
        </div>
    </section>
@endsection
