@extends('template.admin')
@section('title',$title)
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Santri</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ruangan') }}">Ruangan</a></li>
                    <li class="breadcrumb-item active">Data Santri</li>
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
                <a href="{{ route('ruangan') }}" class="btn btn-info mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                @if ($level->can_add)
                <button type="button" id="tambah" class="btn btn-primary mb-3">Tambah Santri</button>
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
              {data: 'nis', name: 'nis'},
              {data: 'nama', name: 'nama'},
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
        $('#modal').find('.modal-body').html(`
        <input type="hidden" name="aksi" id="aksi">
                <input type="hidden" name="id" id="id">
                <div class="form-group">
                    <label for="santri_id">Pilih Santri</label>
                    <select name="santri_id[]" id="santri_id" multiple class="select2 w-100" data-placeholder="Pilih Santri">
                        <option value="">Pilih Santri</option>
                    </select>
                </div>
        `);
        axios.get(`{{ $getSantri }}`)
        .then(res=>{
            let html=''
            res.data.map((row)=>{
                html+=`<option value="${row.id}">${row.nis} ${row.nama}</option>`
            })
            $('#santri_id').html(html)
        })
        $('#modal').find('.modal-title').html('Tambah Data');
        $('#modal').find('#aksi').val('tambah');
        $('#modal').find('#btn').html('Tambah');
        $('#modal').find('.select2').select2({
        theme: 'bootstrap4'
        })
        $('#modal').modal('show')
    });
    @endif
    @if ($level->can_edit)
    $('#data').on('click','.edit',function(){
        $('#modal').find('.modal-body').html(`
        <input type="hidden" name="aksi" id="aksi">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="santri_id" id="santri_id">
                <div class="form-group">
                    <label for="ruangan_id">Pindah Ruangan</label>
                    <select name="ruangan_id" id="ruangan_id" class="select2 w-100" data-placeholder="Pilih Ruangan">
                        <option value="">Pilih Ruangan</option>
                    </select>
                </div>
        `);
        axios.get(`{{ $getRuangan }}`)
        .then(res=>{
            let html=''
            res.data.map((row)=>{
                html+=`<option value="${row.id}">${row.kode_ruangan} ${row.nama_ruangan}</option>`
            })
            $('#ruangan_id').html(html)
        })
        $('#modal').find('#id').val($(this).data('id'));
        $('#modal').find('#santri_id').val($(this).data('santri_id'));
        $('#modal').find('#ruangan_id').val($(this).data('ruangan_id'));
        $('#modal').find('.modal-title').html('Edit Data');
        $('#modal').find('#aksi').val('edit');
        $('#modal').find('#btn').html('Simpan');
        $('#modal').find('.select2').select2({
        theme: 'bootstrap4'
        })
        $('#modal').modal('show')
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
