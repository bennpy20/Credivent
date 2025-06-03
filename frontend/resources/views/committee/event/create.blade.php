<!-- Modal -->
<div class="modal fade" id="modalCreateEvent" tabindex="-1" role="dialog" aria-labelledby="modalCreateEventTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateEventTitle">Tambah event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('committee.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Event</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan nama event">
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" class="form-control" name="location" placeholder="Masukkan lokasi event">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kapasitas Peserta</label>
                                <input type="number" class="form-control" name="max_participants"
                                    placeholder="Masukkan kapasitas">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Tiket</label>
                                <input type="number" class="form-control" name="transaction_fee"
                                    placeholder="Masukkan harga">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="datePicker">Tanggal Mulai</label>
                                <input type="text" name="start_date" id="datePicker" class="form-control"
                                    placeholder="YYYY-MM-DD" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="datePicker">Tanggal Selesai</label>
                                <input type="text" name="end_date" id="datePicker" class="form-control"
                                    placeholder="YYYY-MM-DD" />
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label for="timePicker">Pilih Waktu</label>
                        <input type="text" class="form-control time-picker" placeholder="HH:mm" />
                    </div> --}}

                    <div class="form-group">
                        <label for="inputGroupFile01">Foto Poster</label>
                        <div class="input-group mb-3 form">
                            <div class="custom-file">
                                <input type="file" name="poster_link" class="custom-file-input" id="inputGroupFile01"
                                    accept=".jpg, .png, .jpeg" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label">
                                    Pilih file (.jpg, .png, .jpeg)
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label>Jumlah sesi</label>
                        <input type="number" class="form-control" name="session" placeholder="Masukkan jumlah sesi">
                    </div> --}}

                    <!-- Tambah di bawah input jumlah sesi -->
                    <div class="form-group">
                        <label>Sesi Event</label>
                        <div id="session-container">
                            <!-- Sesi-sesi akan ditambahkan di sini -->
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addSession()">Tambah
                            Sesi</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary">Buat event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="session-template">
    <div class="card p-3 mb-3 position-relative session-block">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="m-0">Sesi <span class="session-number"></span></p>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSession(this)">Batalkan</button>
        </div>

        <div class="form-group">
            <label>Judul Sesi</label>
            <input type="text" name="sessions[__INDEX__][title]" class="form-control" required>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Waktu Mulai</label>
                    <input type="datetime-local" name="sessions[__INDEX__][session_start]" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="datetime-local" name="sessions[__INDEX__][session_end]" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="sessions[__INDEX__][description]" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Nama Pembicara</label>
            <input type="text" name="sessions[__INDEX__][name]" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Foto Pembicara</label>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" name="sessions[__INDEX__][speaker_image]" class="custom-file-input"
                        accept=".jpg, .png, .jpeg">
                    <label class="custom-file-label">Pilih file (.jpg, .png, .jpeg)</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
        var label = e.target.nextElementSibling;
        label.innerText = fileName;
    });
</script>

<script>
    let sessionIndex = 0;

    function addSession() {
        const template = document.getElementById('session-template').innerHTML;
        const html = template.replace(/__INDEX__/g, sessionIndex);
        // Menyisipkan sesi baru ke dalam container
        const sessionContainer = document.getElementById('session-container');
        sessionContainer.insertAdjacentHTML('beforeend', html);

        // Mengupdate nomor sesi di dalam elemen <p>
        const sessionNumber = sessionContainer.querySelectorAll('.session-block')[sessionIndex];
        sessionNumber.querySelector('.session-number').innerText = sessionIndex + 1;

        // Inisialisasi ulang datapicker
        flatpickr(".datetime-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });
        flatpickr(".time-picker", {
            noCalendar: true,
            enableTime: true,
            dateFormat: "H:i",
            time_24hr: true,
        });
        // Inisialisasi ulang utk file input
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
            var label = e.target.nextElementSibling;
            label.innerText = fileName;
        });
        sessionIndex++;
    }

    function removeSession(button) {
        const sessionCard = button.closest('.session-block');
        sessionCard.remove();
    }

    // Update label file name on file change
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('custom-file-input')) {
            const fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file (.jpg atau .png)";
            e.target.nextElementSibling.innerText = fileName;
        }
    });
</script>
