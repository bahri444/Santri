@extends('template.admin')
@section('konten')

<div class="card">
    <div class="card-body">
        <h3>Data Santri di Ruangan</h3>
        <hr>
        <a href="{{ route('ruangan') }}" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>&nbsp;
        <button id="tambah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Data Baru</button>&nbsp;
        <button id="tambahSantri" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Pilih dari data Santri</button>
        <hr>
        <div class="table-responsive">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Santri</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="data">

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal tambah data santri baru -->
<div class="modal" tabindex="-1" id="modalTambah" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Santri</h5>
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

<!-- modal tambah list santri -->
<div class="modal" tabindex="-1" id="modalTambahSantri" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">List Santri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTambahSantri">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="ruangan_id" value="{{ $ruangan_id }}">
                    <select name="santri_id[]" id="santri_id" multiple="multiple" data-placeholder="Pilih Santri" class="select2bs4">
                    </select>
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
                <h5 class="modal-title">Pindah Ruangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="ruangan_santri_id">
                    <input type="hidden" name="santri_id" id="edit_santri_id">
                    <div class="form-group">
                        <select name="ruangan_id" id="ruangan_id" class="form-control">
                            <option value="">Pilih Ruangan</option>
                            @foreach ($ruangan as $r)
                            <option value="{{ $r->id }}">{{ $r->ruangan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Pindah</button>
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
                <h5 class="modal-title">Hapus Ruangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formHapus">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="idHapus">
                    <h4>Apakah Anda Yakin ?</h4>
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
    const form = `  <input type="hidden" name="ruangan_id" id="ruangan_id" value="{{ $ruangan_id }}" >
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
            ajax: `{{ $datatable  }}`,
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
        $('#tambahSantri').click(function() {
            axios.get(`{{ $getSantri }}`)
                .then(res => {
                    let option = ''
                    res.data.forEach(element => {
                        option += `<option value="${element.id}">${element.nama}</option>`
                    });
                    $('#santri_id').html(option)
                })
            $('#modalTambahSantri').modal('show')
        })
        $('#formTambah').submit(function(e) {
            e.preventDefault();
            let data = new FormData(this)
            axios.post(`{{ $urlSantriBaru }}`, data)
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
        $('#formTambahSantri').submit(function(e) {
            e.preventDefault();
            let data = new FormData(this)
            axios.post(`{{ $urlTambah }}`, data)
                .then(res => {
                    table.ajax.reload();
                    $('#modalTambahSantri').modal('hide')
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
            $('#modalEdit').find('#ruangan_santri_id').val($(this).data('id'))
            $('#modalEdit').find('#edit_santri_id').val($(this).data('id_santri'))
            $('#modalEdit').modal('show')
        })
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            let data = new FormData(this)
            axios.post(`{{ $urlEdit }}`, data)
                .then(res => {
                    table.ajax.reload()
                    $('#modalEdit').modal('hide')
                })
        })
        $('#data').on('click', '.hapus', function() {
            $('#idHapus').val($(this).data('id'))
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
        })
    })
</script>
@endsection
