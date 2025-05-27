<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Tambah event</h5>
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
                        <input type="text" id="timePicker" class="form-control" placeholder="HH:mm" />
                    </div> --}}

                    <div class="form-group">
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

<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
        var label = e.target.nextElementSibling;
        label.innerText = fileName;
    });
</script>
