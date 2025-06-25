<!-- Modal Tambah Sesi -->
<div class="modal fade" id="modalTambahSesi{{ $event['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('committee.session.store', $event['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sesi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="event_id" value="{{ $event['id'] }}">
                    <div class="form-group">
                        <label>Sesi Ke</label>
                        <input type="number" name="session" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Judul Sesi</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Mulai</label>
                        <input type="text" name="session_start" class="form-control" id="datetimePicker"
                            placeholder="YYYY-MM-DD HH:MM" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Selesai</label>
                        <input type="text" name="session_end" class="form-control" id="datetimePicker"
                            placeholder="YYYY-MM-DD HH:MM" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nama Pembicara</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Foto Pembicara</label>
                        <input type="file" name="speaker_image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Inisialisasi ulang datapicker
    flatpickr(".datetime-picker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
    });

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".datetime-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
        });
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
