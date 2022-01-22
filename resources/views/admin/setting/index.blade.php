@extends('template.admin')
@section('title',$title)
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengaturan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h3>Pengaturan</h3>
                        <hr>
                        <form action="{{ $simpan }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama Aplikasi</label>
                                <input type="text" name="nama" id="nama" value="{{ $data->nama }}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="copyright">Copyright</label>
                                <input type="text" name="copyright" id="copyright" value="{{ $data->copyright }}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <input type="file" name="logo" accept="image/*" id="logo" class="form-control-input">
                            </div>
                            <div class="mt-2">
                                <img id="preview" src="{{ asset('assets/logo').'/'.$data->logo }}" height="200px" alt="preview">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </form>

                    </div>
                    <div class="d-none d-md-block d-lg-block col-md-6 col-lg-6">
                        <img src="{{ asset('assets/akses.svg') }}" alt="akses" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('#logo').change(function(){
           console.log(true)
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
       });
    })
</script>
@endsection

