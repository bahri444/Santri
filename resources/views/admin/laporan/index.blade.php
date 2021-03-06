@extends('template.admin')
@section('title',$title)
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Pembayaran</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Laporan Pembayaran</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="card direct-chat direct-chat-primary">
              <div class="card-header">
                <h3 class="card-title">Filter</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <form id="filter">
                  @csrf
              <div class="card-body p-2">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-md-4">
                            <div class="form-group">
                                <label for="tahun" >Tahun</label>
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">Pilih Tahun</option>
                                    @foreach ($tahun as $key => $value)
                                    @if (date('Y') == $value->tahun)
                                    <option value="{{$value->tahun}}">{{$value->tahun}}</option>
                                    @else
                                    <option value="{{$value->tahun}}">{{$value->tahun}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-md-4">
                            <div class="form-group">
                                <label for="bulan" >Bulan</label>
                                <select name="bulan" id="bulan" class="form-control">
                                    <option value="">Pilih Bulan</option>
                                    @foreach ($bulan as $key => $value)
                                    @if (date('m')==$key)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @else
                                    <option value="{{ $key }}">{{$value}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-md-4">
                            <div class="form-group">
                                <label for="ruangan_id" >Ruangan</label>
                                <select name="ruangan_id" id="ruangan_id" class="form-control">
                                    <option value="">Pilih Ruangan</option>
                                    @foreach ($ruangan as $r)
                                    <option value="{{$r->id}}">{{ $r->kode_ruangan.' '.$r->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
              </div>
            </div>

        <div class="card">
            <div class="card-body">
                <h3>Laporan Bulanan</h3>
                <hr>
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">NIS</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Ruangan</th>
                            <th  class="text-center">Minggu ini</th>
                            <th  class="text-center">Bulan ini</th>
                            <th  class="text-center">Belum terbayar</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('script')
<script>
    $(document).ready(function(){
        table = $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url:`{{ $urlDatatable }}`,
            type:'post',
            data:function(d){
                d._token= "{{ csrf_token() }}",
                d.tahun=$('#tahun').val(),
                d.bulan=$('#bulan').val(),
                d.ruangan_id=$('#ruangan_id').val()
                }},
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'nis', name: 'nis'},
              {data: 'nama', name: 'nama'},
              {data: 'nama_ruangan', name: 'nama_ruangan'},
              {data: 'minggu', name: 'minggu'},
              {data: 'bulan', name: 'bulan'},
              {data: 'belum', name: 'belum'},
          ]
      });
      $('#filter').change(function(){
          table.ajax.reload();
      })
    });
</script>
@endsection
