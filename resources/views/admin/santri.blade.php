@extends('template.admin')
@section('konten')

<div class="card">
    <div class="card-body">
        <h3>Data Santri</h3>
        <hr>
        <button id="tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Tambah Data</button>
        <hr>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Tempat</th>
                        <th>Tanggal Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data">

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal tambah data santri -->
<div class="modal" tabindex="-1" id="modalTambah" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Santri</h5>
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

<!-- modal Edit data santri -->
<div class="modal" tabindex="-1" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Santri</h5>
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

<!-- modal Hapus data santri -->
<div class="modal" tabindex="-1" id="modalHapus" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Santri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formHapus">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="idHapus">
                    <h4>Apakah Anda Yakin ingin menghapus data santri ?</h4>
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
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" placeholder="masukkan nama" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="jk">Jenis Kelamin</label>
                        <select name="jk" id="jk" class="form-control">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" placeholder="masukkan alamat" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tempat">Tempat</label>
                        <input type="text" name="tempat" id="tempat" placeholder="masukkan tempat tinggal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" placeholder="masukkan tanggal lahir" class="form-control">
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
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'jk',
                    name: 'jk'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'tempat',
                    name: 'tempat'
                },
                {
                    data: 'tgl_lahir',
                    name: 'tgl_lahir'
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
            $('#modalEdit').find('#nama').val($(this).data('nama'))
            $('#modalEdit').find('#jk').val($(this).data('jk'))
            $('#modalEdit').find('#alamat').val($(this).data('alamat'))
            $('#modalEdit').find('#tempat').val($(this).data('tempat'))
            $('#modalEdit').find('#tgl_lahir').val($(this).data('tgl_lahir'))
            $('#modalEdit').modal('show')
        })
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let data = new FormData(this)
            axios.post(`{{ $urlEdit }}`, data)
                .then(res => {
                    table.ajax.reload()
                    $('#modalEdit').modal('hide')
                    toastr['success'](res.data.pesan)
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
        $('#data').on('click', '.hapus', function() {
            $('#modalHapus').find('#idHapus').val($(this).data('id'))
            $('#modalHapus').modal('show')
        })
        $('#formHapus').submit(function(e) {
            e.preventDefault()
            let data = new FormData(this)
            axios.post(`{{ $urlHapus }}`, data)
                .then(res => {
                    $('#modalHapus').modal('hide')
                    table.ajax.reload()
                    toastr['success'](res.data.pesan)
                })
                .catch(err => {
                    if (err.response.status === 500) {
                        toastr['error'](res.response.data.pesan)
                    }
                })
        })
    })
</script>
@endsection
