@extends('templates.main')
@section('title_page', "Detail Materi")

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="my-1">{{ $materi->matkul->kode_matkul }}</h3>
        <h1>{{ $materi->matkul->nama_matkul }}</h1>
        <p class="my-1">{{ $materi->deskripsi }}</p>
        <p class="my-0">
            <strong>Pemateri : {{ $materi->guru->nama_guru }}</strong>
        </p>
    </div>
</div>
<div class="nav-align-top my-4">
    <ul class="nav nav-pills mb-3" role="tablist">
        <li class="nav-item me-3">
            <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#tab-materi">Jobsheet / Sub Materi</button>
        </li>
        <li class="nav-item me-3">
            <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#tab-kelas">Daftar Kelas</button>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-materi" role="tabpanel">
            <h4>MATERI</h4>
            <div class="clearfix mb-3">
                <div class="float-start">
                    <a href="{{ url('guru/materi/'.$id.'/buat-sub-materi') }}" class="btn btn-primary btn-sm">
                        <i class="tf-icons bx bx-plus"></i> Tambah Materi
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
                        <th width="50%">Judul Materi</th>
                        <th width="25%">Tanggal Pengerjaan</th>
                        <th width="10%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materi->materi as $val)
                    <tr>
                        <td>{{ $val->judul_materi }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($val->tanggal_mulai)->format('d/m/Y')}} - {{ \Carbon\Carbon::parse($val->tanggal_selesai)->format('d/m/Y')}}
                        </td>
                        <td>
                            <div class="badge {{ ($val->status == 'Aktif')?'bg-label-success':'bg-label-danger' }}">{{ $val->status }}</div>
                        </td>
                        <td>
                            @if ($val->status == 'Aktif')
                                <a href="{{ url('/guru/materi/submateri/'.$val->id.'/set/0') }}" class="btn btn-link btn-sm text-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" data-bs-original-title="Atur Menjadi Tidak Aktif">
                                    <i class="bx bx-x"></i>
                                </a>
                            @else
                                <a href="{{ url('/guru/materi/submateri/'.$val->id.'/set/1') }}" class="btn btn-link btn-sm text-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" data-bs-original-title="Atur Menjadi Aktif">
                                    <i class="bx bx-check"></i>
                                </a>
                            @endif
                            <a href="{{ url('guru/materi/submateri/edit').'/'.Crypt::encrypt($val->id) }}" class="btn btn-link btn-sm text-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" data-bs-original-title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="tab-kelas" role="tabpanel">
            <h3 class="text-center">KELAS</h3>
        </div>
    </div>
</div>
@endsection


@section('script')
    
@endsection