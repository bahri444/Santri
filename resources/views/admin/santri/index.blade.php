@extends('template.admin')
@section('title',$title)
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Santri</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Santri</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                @if ($level->can_add)
                <button type="button" id="tambah" class="btn btn-primary mb-3">Tambah Data</button>
                @endif
                <div class="table-responsive">
                    <table class="table w-100">
                        <thead>
                            <tr>
                                @foreach ($th as $t)
                                    <th>{{ $t }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="data">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->

{{-- modal --}}

<div class="modal" tabindex="-1" id="modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form">
            <div class="modal-body">
                <input type="hidden" name="aksi" id="aksi">
                <input type="hidden" name="id" id="id">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="nis">Nomor Induk Santri</label>
                            <input type="text" class="form-control" placeholder="Masukkan NIS Santri" name="nis" id="nis" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Santri</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama Santri" name="nama" id="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Santri</label>
                            <input type="text" class="form-control" placeholder="Masukkan Alamat Santri" name="alamat" id="alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" placeholder="Masukkan Tempat Lahir" name="tempat_lahir" id="tempat_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" placeholder="Masukkan Tanggal Lahir" name="tanggal_lahir" id="tanggal_lahir" required>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="jk">Jenis Kelamin</label>
                            <select name="jk" id="jk" class="form-control" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto Santri</label>
                            <input type="file" class="form-control-file" accept="image/*" name="foto" id="foto">
                            <div class="mt-2">
                                <img id="preview" src="{{ asset('assets/foto/default.png') }}" height="200px" alt="preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" id="btn" class="btn btn-primary">Tambah</button>
            </div>
        </form>
      </div>
    </div>
  </div>


{{-- end modal --}}
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function(){

    function preview(){
    $('#modal').find('#foto').change(function(){
           console.log(true)
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#modal').find('#preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
       });
    }

    let table;
    const form=$('#modal').find('.modal-body').html();
    $(function () {
    table = $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ $urlDatatable }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'nis', name: 'nis'},
              {data: 'nama', name: 'nama'},
              {data: 'alamat', name: 'alamat'},
              {data: 'ttl', name: 'ttl'},
              {data: 'jk', name: 'jk'},
              {data: 'foto', name: 'foto'},
              @if ($level->can_edit || $level->can_delete)
              {
                  data: 'action',
                  name: 'action',
                  orderable: true,
                  searchable: true
              },
              @endif
          ]
      });
    });
    @if ($level->can_add)
    $('#tambah').click(()=>{
        $('#modal').find('.modal-dialog').attr('class','modal-dialog modal-xl modal-dialog-centered')
        $('#modal').find('.modal-title').html('Tambah Data');
        $('#modal').find('.modal-body').html(form);
        preview()
        $('#modal').find('#aksi').val('tambah');
        $('#modal').find('#btn').html('Tambah');
        $('#modal').modal('show')
    });
    @endif
    @if ($level->can_edit)
    $('#data').on('click','.edit',function(){
        $('#modal').find('.modal-dialog').attr('class','modal-dialog modal-xl modal-dialog-centered')
        $('#modal').find('.modal-body').html(form);
        axios.get(`{{ url('/admin/santri/detail') }}/${$(this).data('id')}`)
        .then(res=>{
        const {data}=res;
        $('#modal').find('#id').val(data.id);
        $('#modal').find('#nis').val(data.nis);
        $('#modal').find('#nama').val(data.nama);
        $('#modal').find('#tempat_lahir').val(data.tempat_lahir);
        $('#modal').find('#tanggal_lahir').val(data.tanggal_lahir);
        $('#modal').find('#jk').val(data.jenis_kelamin);
        $('#modal').find('#alamat').val(data.alamat);
        $('#modal').find('#preview').attr('src', `{{asset('assets/foto')}}/${data.foto}`);
        $('#modal').find('.modal-title').html('Edit Data');
        preview()
        $('#modal').find('#aksi').val('edit');
        $('#modal').find('#btn').html('Simpan');
        $('#modal').modal('show')
        }).catch(err=>{
            if(err.response.status===404){
                toastr[err.response.data.status](err.response.data.pesan)
                table.ajax.reload()
            }
        })

    })
    @endif
    @if($level->can_delete)
    $('#data').on('click','.hapus',function(){
        $('#modal').find('.modal-dialog').attr('class','modal-dialog modal-dialog-centered')
        $('#modal').find('.modal-body').html(`
        <input type="hidden" name="aksi" value="hapus" />
        <input type="hidden" name="id" value="${$(this).data('id')}"/>
        <h3>Apakah anda yakin ?</h3>
        `);
        $('#modal').find('.modal-title').html('Hapus Data');
        $('#modal').find('#btn').html('Hapus');
        $('#modal').modal('show')
    })
    @endif
    @if ($level->can_add || $level->can_edit || $level->can_delete)
    $('#form').submit(function(e){
        e.preventDefault()
        let data = new FormData(this)
        axios.post(`{{ $aksi }}`,data)
        .then(res=>{
            toastr[res.data.status](res.data.pesan)
            $('#modal').modal('hide')
            table.ajax.reload()
        })
    })
    @endif

})
  </script>
  @endsection
