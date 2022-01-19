@extends('template.admin')
@section('title',$title)
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Ruangan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Ruangan</li>
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
                <div class="form-group">
                    <label for="kode_ruangan">Kode Ruangan</label>
                    <input type="text" class="form-control" name="kode_ruangan" id="kode_ruangan" placeholder="Masukkan Kode Ruangan">
                </div>
                <div class="form-group">
                    <label for="nama_ruangan">Nama Ruangan</label>
                    <input type="text" class="form-control" name="nama_ruangan" id="nama_ruangan" placeholder="Masukkan Nama Ruangan">
                </div>
                <div class="form-group">
                    <label for="pembina_ruangan">Pembina Ruangan</label>
                    <input type="text" class="form-control" name="pembina_ruangan" id="pembina_ruangan" placeholder="Masukkan Pembina Ruangan">
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
    let table;
    const form=$('#modal').find('.modal-body').html();
    $(function () {
    table = $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ $urlDatatable }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'kode_ruangan', name: 'kode_ruangan'},
              {data: 'nama_ruangan', name: 'nama_ruangan'},
              {data: 'pembina_ruangan', name: 'pembina_ruangan'},
              {data: 'santri', name: 'santri'},
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
        $('#modal').find('.modal-title').html('Tambah Data');
        $('#modal').find('.modal-body').html(form);
        $('#modal').find('#aksi').val('tambah');
        $('#modal').find('#btn').html('Tambah');
        $('#modal').modal('show')
    });
    @endif
    @if ($level->can_edit)
    $('#data').on('click','.edit',function(){
        $('#modal').find('.modal-body').html(form);
        axios.get(`{{ url('/admin/ruangan/detail') }}/${$(this).data('id')}`)
        .then(res=>{
        const {data}=res;
        $('#modal').find('#id').val(data.id);
        $('#modal').find('#kode_ruangan').val(data.kode_ruangan);
        $('#modal').find('#nama_ruangan').val(data.nama_ruangan);
        $('#modal').find('#pembina_ruangan').val(data.pembina_ruangan);
        $('#modal').find('.modal-title').html('Edit Data');
        $('#modal').find('#aksi').val('edit');
        $('#modal').find('#btn').html('Simpan');
        $('#modal').modal('show')
        })
        .catch(err=>{
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
