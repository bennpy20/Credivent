@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight"
        style="background-image: url('../memberast/images/member_registered_event.png');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Event Terdaftar</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('member.index') }}">Home <i
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
                                            <form id="delete-form-{{ $registration['registration_id'] }}"
                                                action="{{ route('member.registration.destroy', $registration['registration_id']) }}"
                                                method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmCancel('{{ $registration['registration_id'] }}')">
                                                    Batalkan Pendaftaran
                                                </button>
                                            </form>
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
                                            <a href="#" class="btn btn-success mt-2 view-qrcode"
                                                data-image="{{ $registration['qrcode'] }}">
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

                        <!-- Modal QR Code -->
                        <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog"
                            aria-labelledby="qrCodeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 350px;">
                                <!-- batasi max-width -->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrCodeModalLabel">QR Code Kehadiran Peserta</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="" id="qrCodeImage" class="img-fluid" alt="QR Code"
                                            style="max-width: 100%; height: auto;">
                                        <p class="mt-3 text-muted small">QR Code hanya dapat digunakan pada event ini selama acara
                                            berlangsung.</p>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" class="text-secondary mb-3">
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

    <script>
        $(document).on('click', '.view-proof', function(e) {
            e.preventDefault();
            var imageUrl = $(this).data('image');
            $('#paymentProofImage').attr('src', imageUrl);
            $('#paymentProofMemberModal').modal('show'); // ganti sesuai ID modal kamu
        });
    </script>

    <script>
        // Untuk menampilkan QR code
        $(document).on('click', '.view-qrcode', function(e) {
            e.preventDefault();
            var imageUrl = $(this).data('image');
            $('#qrCodeImage').attr('src', imageUrl);
            $('#qrCodeModal').modal('show');
        });

        // // Bersihkan src saat modal ditutup
        // $('#qrCodeModal').on('hidden.bs.modal', function() {
        //     $('#qrCodeImage').attr('src', '');
        // });
    </script>
@endsection
