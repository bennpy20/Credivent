@extends('components.comp_two.layout')

@section('title')
    Finance Team
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../memberast/images/financeteam_manage_money.png');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Kelola Keuangan</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('member.index') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Kelola Keuangan <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">Daftar Pembayaran Peserta</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    {{-- <th>Email</th> --}}
                                    <th>Nama Event</th>
                                    <th>Harga</th>
                                    <th>Bukti Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($registrations as $registration)
                                    @if ($registration['payment_status'] === 1)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $registration['user']['name'] }}</td>
                                            {{-- <td>ayu@example.com</td> --}}
                                            <td>{{ $registration['event']['name'] }} (Sesi
                                                {{ $registration['session']['session'] }})</td>
                                            <td>Rp
                                                {{ number_format($registration['event']['transaction_fee'], 0, ',', '.') }}
                                            </td>

                                            {{-- <td><span class="badge badge-secondary">Belum Bayar</span></td> --}}
                                            @if ($registration['payment_proof'] != '')
                                                <td><a href="#" class="btn btn-sm btn-info view-proof"
                                                        data-image="{{ $registration['payment_proof'] }}">Lihat</a></td>
                                            @else
                                                <td><span class="text-muted">-</span></td>
                                            @endif
                                            <td class="text-nowrap">
                                                <!-- Tombol Setujui -->
                                                <form id="approve-form-{{ $registration['registration_id'] }}"
                                                    action="{{ route('financeteam.registration.update', $registration['registration_id']) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="2">
                                                </form>
                                                <button type="button" class="btn btn-success btn-sm mr-1"
                                                    onclick="confirmApproval('{{ $registration['registration_id'] }}')">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>

                                                <!-- Tombol Tolak -->
                                                <form id="reject-form-{{ $registration['registration_id'] }}"
                                                    action="{{ route('financeteam.registration.update', $registration['registration_id']) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="3">
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmRejection('{{ $registration['registration_id'] }}')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal Bukti Bayar -->
                                        <div class="modal fade" id="paymentProofModal" tabindex="-1" role="dialog"
                                            aria-labelledby="paymentProofModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="paymentProofModalLabel">Bukti Pembayaran
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="" id="paymentProofImage" class="img-fluid"
                                                            alt="Bukti Pembayaran" style="max-height: 500px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="card shadow mt-5">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">History Pengelolaan Keuangan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    {{-- <th>Email</th> --}}
                                    <th>Nama Event</th>
                                    <th>Harga</th>
                                    <th>Bukti Bayar</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($registrations as $registration)
                                    @if ($registration['payment_status'] != 1)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $registration['user']['name'] }}</td>
                                            {{-- <td>ayu@example.com</td> --}}
                                            <td>{{ $registration['event']['name'] }} (Sesi
                                                {{ $registration['session']['session'] }})</td>
                                            <td>Rp
                                                {{ number_format($registration['event']['transaction_fee'], 0, ',', '.') }}
                                            </td>

                                            {{-- <td><span class="badge badge-secondary">Belum Bayar</span></td> --}}
                                            @if ($registration['payment_proof'] != '')
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-info view-proof"
                                                        data-image="{{ $registration['payment_proof'] }}">Lihat
                                                    </a>
                                                </td>
                                            @else
                                                <td><span class="text-muted">-</span></td>
                                            @endif
                                            <td>{{ $registration['status_display'] }}</td>
                                        </tr>
                                        <!-- Modal Bukti Bayar -->
                                        <div class="modal fade" id="paymentProofModal" tabindex="-1" role="dialog"
                                            aria-labelledby="paymentProofModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="paymentProofModalLabel">Bukti Pembayaran
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Tutup">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="" id="paymentProofImage" class="img-fluid"
                                                            alt="Bukti Pembayaran" style="max-height: 500px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
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
            $('#paymentProofModal').modal('show');
        });
    </script>
@endsection
