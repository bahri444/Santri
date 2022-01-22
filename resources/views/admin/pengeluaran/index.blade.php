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
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" placeholder="Masukkan Nama Barang" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="harga_rp">Harga</label>
                    <input type="text" name="harga_rp" id="harga_rp" placeholder="Masukkan Harga" class="form-control" required>
                    <input type="hidden" name="harga" id="harga" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" id="satuan" placeholder="Masukkan satuan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" placeholder="Masukkan Jumlah Pembelian" min="1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_pembelian">Tanggal Pembelian</label>
                    <input type="date" name="tanggal_pembelian" placeholder="Masukkan Tanggal Pembelian" id="tanggal_pembelian" min="1" class="form-control" required>
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
    function rupiah(){
        let rp = new AutoNumeric('#harga_rp', { currencySymbol : 'Rp. ',decimalCharacter:',',decimalPlaces:'2',digitGroupSeparator: '.' });
		$('#harga_rp').keyup(function() {
			$('#harga').val(rp.get())
		});
    }
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
              {data: 'nama_barang', name: 'nama_barang'},
              {data: 'harga', name: 'harga'},
              {data: 'satuan', name: 'satuan'},
              {data: 'jumlah', name: 'jumlah'},
              {data: 'total', name: 'total'},
              {data: 'tanggal_pembelian', name: 'tanggal_pembelian'},
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
        rupiah()
        $('#modal').modal('show')
    });
    @endif
    @if ($level->can_edit)
    $('#data').on('click','.edit',function(){
        $('#modal').find('.modal-title').html('Edit Data');
        $('#modal').find('.modal-body').html(form);
        $('#modal').find('#aksi').val('edit');
        $('#modal').find('#id').val($(this).data('id'));
        $('#modal').find('#nama_barang').val($(this).data('nama_barang'));
        $('#modal').find('#satuan').val($(this).data('satuan'));
        $('#modal').find('#jumlah').val($(this).data('jumlah'));
        $('#modal').find('#harga_rp').val($(this).data('harga'));
        $('#modal').find('#harga').val($(this).data('harga'));
        $('#modal').find('#tanggal_pembelian').val($(this).data('tanggal_pembelian'));
        $('#modal').find('#btn').html('Simpan');
        rupiah()
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
            table.ajax.reload()
            $('#modal').modal('hide')
        })
    })
    @endif

})
  </script>
  @endsection
