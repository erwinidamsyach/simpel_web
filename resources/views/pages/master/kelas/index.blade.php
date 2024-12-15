@extends('templates.main')
@section('title_page', "Master Data Kelas & Siswa")

@section('content')
<div class="card">
    <div class="card-body">
        <div class="clearfix">
            <div class="float-start">
                <a href="{{ url(Request::path()."/add") }}" class="btn btn-primary btn-sm">
                    <i class="tf-icons bx bx-plus"></i> Buat Kelas Baru
                </a>
            </div>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkatan</th>
                    <th>Jumlah Siswa</th>
                    <th>Action</th>                    
                </tr>
            </thead>
            <tbody>
                @foreach ($data_kelas as $item)
                    <tr>
                        <td>{{ $item->nama_kelas }}</td>
                        <td>{{ $item->tingkatan }}</td>
                        <td>{{ $item->siswa_count }} Siswa</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-link text-muted px-0" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url(Request::path()."/detail")."/".Crypt::encrypt($item->id) }}" class="dropdown-item">Detail</a></li>
                                    <li><a href="{{ url(Request::path()."/delete")."/".Crypt::encrypt($item->id) }}" class="dropdown-item text-danger">Hapus</a></li>
                                </ul>
                            </div>                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

