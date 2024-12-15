@extends('templates.main')
@section('title_page', "Materi")

@section('content')
<div class="card">
    <h5 class="card-header">Daftar Materi</h5>
    <div class="card-body">
        <div class="clearfix mb-3">
            <div class="float-start">
                <a href="{{ url('guru/materi/buat-materi') }}" class="btn btn-primary btn-sm">
                    <i class="tf-icons bx bx-plus"></i> Buat Materi
                </a>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th width="30%">Matkul</th>
                    <th width="30%">Deskripsi</th>
                    <th width="15%">Sub Materi</th>
                    <th width="15%">Jumlah Kelas</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_materi as $item)
                    <tr>
                        <td>
                            {{ $item->matkul->kode_matkul }}<br/>
                            {{ $item->matkul->nama_matkul }}
                        </td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ $item->materi_count }} Submateri</td>
                        <td>{{ $item->kelas_count }} Kelas</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-link text-muted px-0" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/guru/materi/').'/'.Crypt::encrypt($item->id) }}" class="dropdown-item">Kelola</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="{{ url(Request::path()."/delete")."/".Crypt::encrypt($item->id) }}" class="dropdown-item text-danger">Hapus Materi</a></li>
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

@section('script')    
@endsection