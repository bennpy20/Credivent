<!-- Modal -->
<div class="modal fade" id="modalEditSesi{{ $event['id'] }}" tabindex="-1" role="dialog"
    aria-labelledby="modalEditSesiTitle{{ $event['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="{{ route('committee.session.update', $event['id']) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sesi Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($event['event_sessions'] as $index => $session)
                        <div class="card p-3 mb-4 border session-block">
                            <input type="hidden" name="sessions[{{ $index }}][id]" value="{{ $session['id'] }}">
                            <div class="form-group">
                                <label>Judul Sesi</label>
                                <input type="text" name="sessions[{{ $index }}][title]" class="form-control"
                                    value="{{ $session['title'] }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Waktu Mulai</label>
                                    <input type="text" name="sessions[{{ $index }}][session_start]"
                                        class="form-control datetime-picker" id="datetimePicker"
                                        value="{{ $session['session_start'] }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Waktu Selesai</label>
                                    <input type="text" name="sessions[{{ $index }}][session_end]"
                                        class="form-control datetime-picker" id="datetimePicker"
                                        value="{{ $session['session_end'] }}" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label>Deskripsi</label>
                                <textarea name="sessions[{{ $index }}][description]" class="form-control" required>{{ $session['description'] }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Nama Pembicara</label>
                                <input type="text" name="sessions[{{ $index }}][name]" class="form-control"
                                    value="{{ $session['name'] }}" required>
                            </div>
                            <div class="form-group">
                                <label>Foto Pembicara</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" name="sessions[{{ $index }}][speaker_image]"
                                            class="custom-file-input" accept=".jpg,.jpeg,.png">
                                        <label class="custom-file-label">Pilih file baru jika ingin mengganti</label>
                                    </div>
                                </div>
                                <small>Foto saat ini: {{ $session['speaker_image'] }}</small>
                                <input type="hidden" name="sessions[{{ $index }}][old_speaker_image]"
                                    value="{{ $session['speaker_image'] }}">
                            </div>
                            <button type="button" class="btn btn-sm btn-danger mt-2"
                                onclick="confirmDelete('{{ $session['id'] }}')">
                                Hapus Sesi
                            </button>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            @foreach ($event['event_sessions'] as $session)
                <form id="delete-form-{{ $session['id'] }}"
                    action="{{ route('committee.session.destroy', $session['id']) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>
</div>

<script>
    function deleteSession(sessionId) {
        if (!confirm('Yakin ingin menghapus sesi ini?')) return;

        fetch(`/committee/session/${sessionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal menghapus sesi');
                return response.json();
            })
            .then(data => {
                alert('Sesi berhasil dihapus');
                location.reload(); // Atau hapus elemen card-nya dari DOM
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan saat menghapus sesi');
            });
    }
</script>
