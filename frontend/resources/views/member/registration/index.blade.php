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
                    <h1 class="mb-3 bread">Event Terdaftar</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Event Terdaftar <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            @if (!empty($registrations) && count($registrations) > 0)
                <div class="row d-flex">
                    @foreach ($registrations as $registration)
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="blog-entry justify-content-end">
                                <a href="#" class="block-20"
                                    style="background-image: url('{{ $registration['event']['poster_link'] }}');">
                                </a>
                                <div class="text p-4 float-right d-block">
                                    <h3 class="heading mt-2"><strong>{{ $registration['event']['name'] }}</strong></h3>
                                    <div class="d-flex align-items-center pt-2 mb-4">
                                        <div class="event-date-display">{{ $registration['event']['date_display'] }}
                                        </div>
                                    </div>
                                    <p>Sesi {{ $registration['session']['session'] }}
                                    </p>
                                    <p>Biaya Tiket: Rp
                                        {{ number_format($registration['event']['transaction_fee'], 0, ',', '.') }}
                                    </p>
                                    @switch($registration['registration_status'])
                                        @case('bayar')
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modalPayment{{ $registration['registration_id'] }}">
                                                Upload Bukti Pembayaran
                                            </button>
                                        @break

                                        @case('diproses')
                                            <a href="#" class="btn btn-outline-info mt-2 view-proof"
                                                data-image="{{ $registration['payment_proof'] }}">
                                                <i class="fas fa-receipt pr-2"></i> Lihat Bukti Pembayaran
                                            </a>
                                            <div class="alert alert-info d-flex align-items-center p-2 mt-2 rounded-sm shadow-sm"
                                                role="alert">
                                                <i class="fas fa-hourglass-half px-1 mr-2"></i>
                                                Sedang Diverifikasi
                                            </div>
                                        @break

                                        @case('sukses')
                                            <a href="#" class="btn btn-outline-info mt-2 view-proof"
                                                data-image="{{ $registration['payment_proof'] }}">
                                                <i class="fas fa-receipt pr-2"></i> Lihat Bukti Pembayaran
                                            </a>
                                            <a href="#" class="btn btn-success mt-2">
                                                <i class="fas fa-qrcode pr-2"></i> Tampilkan QR Code
                                            </a>
                                        @break

                                        @default
                                            <div class="alert alert-danger p-2 mt-2 mb-0">
                                                <strong>Pembayaran Gagal</strong><br>
                                                Bukti pembayaran belum valid. Silakan registrasi ulang event
                                            </div>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                        <!-- Modal Bukti Pembayaran Member -->
                        <div class="modal fade" id="paymentProofMemberModal" tabindex="-1" role="dialog"
                            aria-labelledby="paymentProofMemberModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentProofMemberModalLabel">Bukti
                                            Pembayaran</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="" id="paymentProofImage" class="img-fluid" alt="Bukti Pembayaran"
                                            style="max-height: 500px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('member.registration.edit', ['registration' => $registration])
                    @endforeach
                </div>
            @else
                <!-- Jika kosong -->
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" class="text-secondary mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.75 9.75h.008v.008H9.75V9.75zm4.5 0h.008v.008H14.25V9.75zM12 15.75c1.5 0 2.25-.75 2.25-.75s-.75-1.5-2.25-1.5-2.25 1.5-2.25 1.5.75.75 2.25.75zm0 6.75a9.75 9.75 0 100-19.5 9.75 9.75 0 000 19.5z" />
                    </svg>

                    <h4 class="text-muted">Belum ada pendaftaran event</h4>
                    <p class="text-secondary">Silakan registrasi event terlebih dahulu</p>
                </div>
                <hr class="mb-4" style="border-top: 2px solid #dee2e6;">
            @endif
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

    <script>
        $(document).on('click', '.view-proof', function(e) {
            e.preventDefault();
            var imageUrl = $(this).data('image');
            $('#paymentProofImage').attr('src', imageUrl);
            $('#paymentProofMemberModal').modal('show'); // ganti sesuai ID modal kamu
        });
    </script>
@endsection
