<div class="modal fade" id="modalPayment{{ $registration['registration_id'] }}" tabindex="-1" role="dialog" aria-labelledby="modalPaymentTitle{{ $registration['registration_id'] }}"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPaymentTitle{{ $registration['registration_id'] }}">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('member.registration.update', ['id' => $registration['registration_id']]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputGroupFile01">Upload Bukti Pembayaran</label>
                        <div class="input-group mb-3 form">
                            <div class="custom-file">
                                <input type="file" name="payment_proof" class="custom-file-input" id="inputGroupFile01"
                                    accept=".jpg, .png, .jpeg" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label">
                                    Pilih file (.jpg, .png, .jpeg)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Bayar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function (e) {
            let fileName = e.target.files[0].name;
            e.target.nextElementSibling.innerText = fileName;
        });
    });
</script>