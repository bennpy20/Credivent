@extends('components.comp_two.layout')

@section('title')
    Finance Team
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../memberast/images/bg_2.jpg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Kelola Keuangan</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
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

    <section class="ftco-section contact-section">
        <div class="container">
            <div class="row d-flex mb-5 contact-info">
                <div class="col-md-12 mb-4">
                    <h2 class="h3">Event berlangsung</h2>
                </div>
                <div class="w-100"></div>
                <div class="col-md-3">
                    <p><span>Address:</span> 198 West 21th Street, Suite 721 New York NY 10016</p>
                </div>
                <div class="col-md-3">
                    <p><span>Phone:</span> <a href="tel://1234567920">+ 1235 2355 98</a></p>
                </div>
                <div class="col-md-3">
                    <p><span>Email:</span> <a href="mailto:info@yoursite.com">info@yoursite.com</a></p>
                </div>
                <div class="col-md-3">
                    <p><span>Website</span> <a href="#">yoursite.com</a></p>
                </div>
            </div>
            <div class="row block-9">
                <div class="col-md-6 order-md-last d-flex">
                    <form action="#" class="bg-light p-5 contact-form">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Email">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Subject">
                        </div>
                        <div class="form-group">
                            <textarea name="" id="" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
                        </div>
                    </form>

                </div>

                <div class="col-md-6 d-flex">
                    <div id="map" class="bg-white"></div>
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

    <script>
        $(document).on('click', '.view-proof', function(e) {
            e.preventDefault();
            var imageUrl = $(this).data('image');
            $('#paymentProofImage').attr('src', imageUrl);
            $('#paymentProofModal').modal('show');
        });
    </script>
@endsection
