@extends('template.admin')
@section('title',$title)
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
<<<<<<< HEAD
                        <span class="info-box-text"><a href="{{route('santri')}}">Santri</a></span>
                        <span class="info-box-number">{{$santri}}</span>
=======
                        <span class="info-box-text">Santri</span>
                        <span class="info-box-number">{{ $santri }}</span>
>>>>>>> main
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-building"></i></span>

                    <div class="info-box-content">
<<<<<<< HEAD
                        <span class="info-box-text"><a href="{{route('ruangan')}}">Ruangan</a></span>
                        <span class="info-box-number">{{$ruangan}}</span>
=======
                        <span class="info-box-text">Ruangan</span>
                        <span class="info-box-number">{{ $ruangan }}</span>
>>>>>>> main
                    </div>
                </div>
            </div>
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-list"></i></span>

                    <div class="info-box-content">
<<<<<<< HEAD
                        <span class="info-box-text"><a href="{{route('pembayaran')}}">Pembayaran Minggu ini</a></span>
                        <span class="info-box-number">{{$pembayaran}}</span>
=======
                        <span class="info-box-text">Pembayaran Minggu ini</span>
                        <span class="info-box-number">{{ $minggu }}</span>
>>>>>>> main
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
<<<<<<< HEAD
                        <span class="info-box-text"><a href="{{route('tagihan')}}">Pembayaran Bulan ini</a></span>
                        <span class="info-box-number">{{$tagihan}}</span>
=======
                        <span class="info-box-text">Pembayaran Bulan ini</span>
                        <span class="info-box-number">{{ $bulan }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pengeluaran Minggu ini</span>
                        <span class="info-box-number">{{ $totalPengeluaran }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pengeluaran Bulan ini</span>
                        <span class="info-box-number">{{ $totalpengBulan }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pemasukan Minggu ini</span>
                        <span class="info-box-number">{{ $totalPemasukan }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pemasukan Bulan ini</span>
                        <span class="info-box-number">{{ $totalPemasukanBulan }}</span>
>>>>>>> main
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection