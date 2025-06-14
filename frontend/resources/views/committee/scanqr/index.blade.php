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
                    <h1 class="mb-3 bread">Scan QR Code</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Scan QR Code <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-light">
        <div class="container py-4" style="max-width: 500px;">
            <div class="card shadow">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Scan QR Code Peserta</h5>
                    <div>
                        <button id="start-btn" class="btn btn-light btn-sm">Mulai Scan</button>
                        <button id="stop-btn" class="btn btn-danger btn-sm d-none">Berhenti</button>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div id="reader" style="width: 300px; margin: auto;"></div>
                    <div id="scan-result" class="mt-4"></div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const resultBox = document.getElementById("scan-result");
        const startBtn = document.getElementById("start-btn");
        const stopBtn = document.getElementById("stop-btn");

        let html5QrCode = new Html5Qrcode("reader");
        let isScanning = false;

        async function onScanSuccess(decodedText, decodedResult) {
            stopScan();

            resultBox.innerHTML = `<div class="text-info">Memverifikasi QR code...</div>`;

            const response = await fetch("{{ route('committee.scanqr.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    qr_data: decodedText
                })
            });

            const data = await response.json();
            if (data.valid) {
                resultBox.innerHTML = `
                <div class="alert alert-success">
                    Valid!<br>
                    Event: <strong>${data.detail.event}</strong><br>
                    Sesi: <strong>${data.detail.session}</strong><br>
                    Waktu: ${data.detail.waktu}
                </div>`;
            } else {
                resultBox.innerHTML =
                    `<div class="alert alert-danger">Tidak valid: ${data.message || 'Terjadi kesalahan saat verifikasi'}</div>`;
            }

            // Mulai ulang scan setelah 3,5 detik
            setTimeout(() => {
                resultBox.innerHTML = "";
                startScan();
            }, 3500);
        }

        function startScan() {
            if (isScanning) return;

            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 300,
                        height: 300
                    },
                    aspectRatio: 1.0
                }, onScanSuccess)
                .then(() => {
                    isScanning = true;
                    startBtn.classList.add("d-none");
                    stopBtn.classList.remove("d-none");
                })
                .catch(err => {
                    resultBox.innerHTML = `<div class="alert alert-danger">Gagal memulai kamera: ${err}</div>`;
                });
        }

        function stopScan() {
            if (!isScanning) return;

            html5QrCode.stop()
                .then(() => {
                    isScanning = false;
                    stopBtn.classList.add("d-none");
                    startBtn.classList.remove("d-none");
                })
                .catch(err => {
                    console.error("Gagal berhenti scan:", err);
                });
        }

        // Tombol kendali
        startBtn.addEventListener("click", startScan);
        stopBtn.addEventListener("click", stopScan);
    </script>
@endsection
