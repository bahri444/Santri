@extends('template.admin')
@section('title',$title)
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pembayaran Santri</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pembayaran Santri</li>
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

<div class="modal"  id="modal" role="dialog">
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
                    <label for="santri_id">Nama Santri</label>
                    <select name="santri_id" id="santri_id" class="select2 form-control" required>
                        <option value="">Pilih Santri</option>
                        @foreach ($santri as $s)
                            <option value="{{ $s->id }}">{{$s->nis}} {{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tagihan_id">Tagihan</label>
                    <select name="tagihan_id" id="Tagihan" class="select2 form-control" required>
                        <option value="">Pilih Tagihan</option>
                        @foreach ($tagihan as $s)
                            <option value="{{ $s->id }}">{{$s->nama_tagihan}} {{ $s->tagihan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="rupiah">Pembayaran</label>
                    <input type="text" name="rupiah" id="rupiah" class="form-control">
                    <input type="hidden" id="pembayaran" name="pembayaran">
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
    function select2(){
        $('#modal').find('.select2').select2({
        theme: 'bootstrap4'
        })
    }
    function rupiah(){
        let rp = new AutoNumeric('#rupiah', { currencySymbol : 'Rp. ',decimalCharacter:',',decimalPlaces:'2',digitGroupSeparator: '.' });
		$('#rupiah').keyup(function() {
			$('#tagihan').val(rp.get())
		});
    }
    let table;
    const form=$('#modal').find('.modal-body').html();
    $(function () {
    table = $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url:`{{ $urlDatatable }}`,
            type:'GET',

              },
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'nis', name: 'nis'},
              {data: 'nama', name: 'nama'},
              {data: 'rupiah', name: 'rupiah'},
              {data: 'pembayaran', name: 'pembayaran'},
              {data: 'sisa', name: 'sisa'},
              {data: 'status', name: 'status'},
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
        rupiah()
        select2()
        $('#modal').find('#aksi').val('tambah');
        $('#modal').find('#btn').html('Tambah');
        $('#modal').modal('show')
    });
    @endif
    @if ($level->can_edit)
    $('#data').on('click','.edit',function(){
        $('#modal').find('.modal-title').html('Edit Data');
        $('#modal').find('.modal-body').html(form);
        $('#modal').find('#aksi').val('edit');
        $('#modal').find('#id').val($(this).data('id'));
        $('#modal').find('#nama_tagihan').val($(this).data('nama_tagihan'));
        $('#modal').find('#rupiah').val($(this).data('tagihan'));
        $('#modal').find('#tagihan').val($(this).data('tagihan'));
        $('#modal').find('#keterangan').val($(this).data('keterangan'));
        rupiah()
        $('#modal').find('#btn').html('Simpan');
        $('#modal').modal('show')
    })
    @endif
    @if($level->can_delete)
    $('#data').on('click','.hapus',function(){
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
