@extends('template.admin')
@section('konten')
<!-- Small boxes (Stat box) -->
<div class="row">
    @if(Auth::user()->level == 'admin')
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $user }}</h3>

                <p>User</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <a href="{{route('user')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $santri }}</h3>

                <p>Santri</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-people"></i>
            </div>
            <a href="{{ route('santri') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $ruangan }}</h3>

                <p>Ruangan</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-home"></i>
            </div>
            <a href="{{ route('ruangan') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>0</h3>

                <p>Catering bulan ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-clipboard"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row (main row) -->
@endsection
