@extends('template.admin')
@section('konten')

<div class="card">
    <div class="card-body">
        <h3>Histori Belanja</h3>
        <hr>
        <button id="tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Tambah Pembayaran</button>
        <hr>
        <div class="table-responsive">
            <table class="table  w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id Santri</th>
                        <th>Jumlah Bayar</th>
                        <th>Tanggal Bayar</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data">

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal tambah data Pembayaran -->
<div class="modal" tabindex="-1" id="modalTambah" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Histori Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTambah">
                @csrf
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal Edit Pembayaran -->
<div class="modal" tabindex="-1" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Histori Pembayaran </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit">
                @csrf
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal Hapus pembayaran -->
<div class="modal" tabindex="-1" id="modalHapus" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Histori Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formHapus">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="idHapus">
                    <h4>Apakah Anda Yakin ingin menghapus histori pembayaran ?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const form = `
    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="santri_id">Id Santri</label>
                        <input type="text" name="santri_id" id="santri_id" placeholder="masukkan id santri" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="jml_bayar">Jumlah Bayar</label>
                        <input type="text" name="jml_bayar" id="jml_bayar" placeholder="masukkan jumlah bayar" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tgl_bayar">Tanggal Bayar</label>
                        <input type="date" name="tgl_bayar" id="tgl_bayar" placeholder="" class="form-control">
                    </div>`
    $(document).ready(function() {
        let table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            ajax: `{{ $datatable }}`,
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'santri_id',
                    name: 'santri_id'
                },
                {
                    data: 'jml_bayar',
                    name: 'jml_bayar'
                },
                {
                    data: 'tgl_bayar',
                    name: 'tgl_bayar'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ]
        })
        $('#tambah').click(function() {
            $('#modalTambah').find('.modal-body').html(form)
            $('#modalTambah').modal('show')
        })
        $('#formTambah').submit(function(e) {
            e.preventDefault();
            let data = new FormData(this)
            axios.post(`{{ $urlTambah }}`, data)
                .then(res => {
                    table.ajax.reload();
                    $('#modalTambah').modal('hide')
                    toastr['success'](res.data.pesan)
                })
                .catch(err => {
                    if (err.response.status === 401) {
                        toastr['error']("Field tidak boleh kosong")
                    }
                    if (err.response.status === 500) {
                        toastr['error'](err.response.data.pesan)
                    }
                })
        })
        $('#data').on('click', '.edit', function() {
            $('#modalEdit').find('.modal-body').html(form)
            $('#modalEdit').find('#id').val($(this).data('id'))
            $('#modalEdit').find('#santri_id').val($(this).data('santri_id'))
            $('#modalEdit').find('#jml_bayar').val($(this).data('jml_bayar'))
            $('#modalEdit').find('#tgl_bayar').val($(this).data('tgl_bayar'))
            $('#modalEdit').modal('show')
        })
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let data = new FormData(this)
            axios.post(`{{ $urlEdit }}`, data)
                .then(result => {
                    table.ajax.reload()
                    $('#modalEdit').modal('hide')
                    toastr['success'](result.data.pesan)
                })
                .catch(err => {
                    if (err.response.status === 401) {
                        toastr['error']("Field tidak boleh kosong")
                    }
                    if (err.response.status === 500) {
                        toastr['error']('data tidak ada')
                    }
                })
        })
        $('#data').on('click', '.hapus', function() {
            $('#modalHapus').find('#idHapus').val($(this).data('id'))
            $('#modalHapus').modal('show')
        })
        $('#formHapus').submit(function(e) {
            e.preventDefault()
            let data = new FormData(this)
            axios.post(`{{ $urlHapus }}`, data)
                .then(result => {
                    $('#modalHapus').modal('hide')
                    table.ajax.reload()
                    toastr['success'](result.data.pesan)
                })
                .catch(err => {
                    if (err.response.status === 500) {
                        toastr['error'](result.response.data.pesan)
                    }
                })
        })
    })
</script>
@endsection