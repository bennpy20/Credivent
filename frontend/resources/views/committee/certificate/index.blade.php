@extends('components.comp_two.layout')

@section('title')
    Committee
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight" style="background-image: url('../memberast/images/bg_2.jpg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Upload Sertifikat</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Upload Sertifikat <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container">
            <h4 class="mb-4 text-center"><strong>Daftar Upload Sertifikat Peserta</strong></h4>

            <table class="table table-bordered table-hover">
                <thead class="table-secondary text-center">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Peserta</th>
                        <th class="p-3">Nama Event</th>
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Upload Sertifikat</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $hasData = false;
                    @endphp
                    @foreach ($attendances as $attendance)
                        @if ($attendance['certificate_link'] === '')
                            @php $hasData = true; @endphp
                            <tr>
                                <td class="align-middle text-center">{{ $no++ }}</td>
                                <td class="align-middle text-nowrap">{{ $attendance['user']['name'] }}</td>
                                <td class="align-middle text-nowrap">{{ $attendance['event']['name'] }} (Sesi
                                    {{ $attendance['session']['session'] }})</td>
                                <td class="align-middle text-nowrap">{{ $attendance['session']['session_range'] }}
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('committee.certificate.update', $attendance['attendance_id']) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="certificate_link" class="custom-file-input"
                                                    id="inputGroupFile_{{ $attendance['attendance_id'] }}"
                                                    accept=".pdf, .jpg, .png, .jpeg" required>
                                                <label class="custom-file-label"
                                                    for="inputGroupFile_{{ $attendance['attendance_id'] }}">
                                                    Pilih file (.pdf, .jpg, .png, .jpeg)
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-sm" type="submit">
                                                    <i class="fas fa-upload"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    @if (!$hasData)
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada data peserta yang menunggu
                                untuk upload sertifikat</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <h4 class="mt-5 mb-4 text-center"><strong>History Upload Sertifikat Peserta</strong></h4>

            <table class="table table-bordered table-hover">
                <thead class="table-secondary text-center">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Peserta</th>
                        <th class="p-3">Nama Event</th>
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Sertifikat</th>
                        <th class="p-3">Upload Ulang</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $hasHistory = false;
                    @endphp
                    @foreach ($attendances as $attendance)
                        @if ($attendance['certificate_link'] != '')
                            @php $hasHistory = true; @endphp
                            <tr>
                                <td class="align-middle text-center">{{ $no++ }}</td>
                                <td class="align-middle text-nowrap">{{ $attendance['user']['name'] }}</td>
                                <td class="align-middle text-nowrap">{{ $attendance['event']['name'] }} (Sesi
                                    {{ $attendance['session']['session'] }})</td>
                                <td class="align-middle text-nowrap">{{ $attendance['session']['session_range'] }}
                                </td>
                                <td class="align-middle">
                                    <a href="{{ $attendance['certificate_link'] }}" class="btn btn-success btn-sm"
                                        target="_blank">
                                        <i class="fas fa-file-download"></i> Download
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <form
                                        action="{{ route('committee.certificate.update', $attendance['attendance_id']) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="certificate_link" class="custom-file-input"
                                                    id="inputGroupFile_{{ $attendance['attendance_id'] }}"
                                                    accept=".pdf, .jpg, .png, .jpeg" required>
                                                <label class="custom-file-label"
                                                    for="inputGroupFile_{{ $attendance['attendance_id'] }}">
                                                    Pilih file baru
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-sm" type="submit">
                                                    <i class="fas fa-upload"></i> Upload Ulang
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    @if (!$hasHistory)
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada history upload sertifikat</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>


    <script>
        document.querySelectorAll('.custom-file-input').forEach(input => {
            input.addEventListener('change', function(e) {
                let fileName = e.target.files[0].name;
                e.target.nextElementSibling.innerText = fileName;
            });
        });
    </script>
@endsection
