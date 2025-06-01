<!-- Modal -->
<div class="modal fade" id="modalUpdateEvent{{ $event['id'] }}" tabindex="-1" role="dialog"
    aria-labelledby="modalUpdateEventTitle{{ $event['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUpdateEventTitle">Update event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('committee.event.store', $event['id']) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Event</label>
                        <input type="text" class="form-control" name="name" value="{{ $event['name'] }}">
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" class="form-control" name="location" value="{{ $event['location'] }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kapasitas Peserta</label>
                                <input type="number" class="form-control" name="max_participants"
                                    value="{{ $event['max_participants'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Tiket</label>
                                <input type="number" class="form-control" name="transaction_fee"
                                    value="{{ $event['transaction_fee'] }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="datePicker">Tanggal Mulai</label>
                                <input type="text" name="start_date" id="datePicker" class="form-control"
                                    value="{{ $event['start_date'] }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="datePicker">Tanggal Selesai</label>
                                <input type="text" name="end_date" id="datePicker" class="form-control"
                                    value="{{ $event['end_date'] }}" />
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label for="timePicker">Pilih Waktu</label>
                        <input type="text" class="form-control time-picker" placeholder="HH:mm" />
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="inputGroupFile01">Foto Poster</label>
                        <div class="input-group mb-3 form">
                            <div class="custom-file">
                                <input type="file" name="poster_link" class="custom-file-input" id="inputGroupFile01"
                                    accept=".jpg, .png" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label">
                                    Pilih file (.jpg atau .png)
                                </label>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="form-group">
                        <label>Jumlah sesi</label>
                        <input type="number" class="form-control" name="session" placeholder="Masukkan jumlah sesi">
                    </div> --}}

                    <!-- Tambah di bawah input jumlah sesi -->
                    <div class="form-group">
                        <label>Sesi Event</label>
                        <div id="edit-session-container"></div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addSessionEdit()">Tambah
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
                    <input type="text" name="sessions[__INDEX__][session_start]" id="timePicker" class="form-control"
                        placeholder="HH:MM" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Waktu Selesai</label>
                    <input type="text" name="sessions[__INDEX__][session_end]" id="timePicker" class="form-control"
                        placeholder="HH:MM" required>
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
                        accept=".jpg, .png">
                    <label class="custom-file-label">Pilih file (.jpg atau .png)</label>
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
    let sessionIndexEdit = 0;

    function openEditModal(eventData) {
        const container = document.getElementById('edit-session-container');
        container.innerHTML = '';
        sessionIndexEdit = 0;

        eventData.event_sessions.forEach((session, i) => {
            const html = document.getElementById('session-template').innerHTML
                .replace(/__INDEX__/g, sessionIndexEdit)
                .replace('value=""', `value="${session.title}"`);

            container.insertAdjacentHTML('beforeend', html);
            container.querySelectorAll('.session-number')[i].innerText = i + 1;

            // Manual set value
            const block = container.querySelectorAll('.session-block')[i];
            block.querySelector(`[name="sessions[${i}][title]"]`).value = session.title;
            block.querySelector(`[name="sessions[${i}][session_start]"]`).value = session.session_start;
            block.querySelector(`[name="sessions[${i}][session_end]"]`).value = session.session_end;
            block.querySelector(`[name="sessions[${i}][description]"]`).value = session.description;
            block.querySelector(`[name="sessions[${i}][name]"]`).value = session.name;

            sessionIndexEdit++;
        });

        $('#modalEditEvent').modal('show');
    }

    function addSessionEdit() {
        const template = document.getElementById('session-template').innerHTML;
        const html = template.replace(/__INDEX__/g, sessionIndexEdit);
        const container = document.getElementById('edit-session-container');
        container.insertAdjacentHTML('beforeend', html);
        const sessionNumber = container.querySelectorAll('.session-block')[sessionIndexEdit];
        sessionNumber.querySelector('.session-number').innerText = sessionIndexEdit + 1;
        sessionIndexEdit++;
    }
</script>