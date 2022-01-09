@extends('template.admin')
@section('konten')

<div class="card">
    <div class="card-body">
        <h3>Histori Belanja</h3>
        <hr>
        <button id="tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Tambah Belanjaan</button>
        <hr>
        <div class="table-responsive">
            <table class="table  w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Satuan</th>
                        <th>Harga Item</th>
                        <th>Total Harga</th>
                        <th>Tanggal belanja</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data">

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal tambah data belanjaan -->
<div class="modal" tabindex="-1" id="modalTambah" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Histori Belanja</h5>
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

<!-- modal Edit pengeluaran belanja -->
<div class="modal" tabindex="-1" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Histori belanja </h5>
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

<!-- modal Hapus pengeluaran belanja -->
<div class="modal" tabindex="-1" id="modalHapus" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Histori belanja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formHapus">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="idHapus">
                    <h4>Apakah Anda Yakin ingin menghapus histori belanja ?</h4>
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
                        <label for="nm_barang">Nama barang</label>
                        <input type="text" name="nm_barang" id="nm_barang" placeholder="masukkan nama barang" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="jml">Jumlah Barang</label>
                        <input type="text" name="jml" id="jml" placeholder="masukkan jumlah barang" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" name="satuan" id="satuan" placeholder="masukkan satuan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="harga_item">Harga Item</label>
                        <input type="text" name="harga_item" id="harga_item" placeholder="masukkan harga per item" class="form-control">
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
                    data: 'nm_barang',
                    name: 'nm_barang'
                },
                {
                    data: 'jml',
                    name: 'jml'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
                },
                {
                    data: 'harga_item',
                    name: 'harga_item'
                },
                //menampilkan harga total
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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
                })
                .catch(err => {
                    if (err.response.status === 401) {
                        toastr['error']("Field tidak boleh kosong")
                    }
                    if (err.response.status === 500) {
                        toastr['error'](res.response.data.pesan)
                    }
                })
        })
        $('#data').on('click', '.edit', function() {
            $('#modalEdit').find('.modal-body').html(form)
            $('#modalEdit').find('#id').val($(this).data('id'))
            $('#modalEdit').find('#nm_barang').val($(this).data('nm_barang'))
            $('#modalEdit').find('#jml').val($(this).data('jml'))
            $('#modalEdit').find('#satuan').val($(this).data('satuan'))
            $('#modalEdit').find('#harga_item').val($(this).data('harga_item'))
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