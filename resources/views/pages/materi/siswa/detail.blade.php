@extends('templates.main')
@section('title_page', "Detail Materi")

@section('content')
<div class="card">
    <div class="card-body">
        <h3 class="my-1">{{ $main_materi->matkul->kode_matkul }}</h3>
        <h1>{{ $main_materi->matkul->nama_matkul }}</h1>
        <p class="my-1">{{ $main_materi->deskripsi }}</p>
        <p class="my-0">
            <strong>Pemateri : {{ $main_materi->guru->nama_guru }}</strong>
        </p>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th width="50%">Judul Materi</th>
                    <th width="25%">Tanggal Pengerjaan</th>
                    <th width="10%">Status</th>
                    <th>Nilai</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materi as $val)
                <tr>
                    <td>{{ $val->judul_materi }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($val->tanggal_mulai)->format('d/m/Y')}} - {{ \Carbon\Carbon::parse($val->tanggal_selesai)->format('d/m/Y')}}
                    </td>
                    <td>
                        <div class="badge {{ ($val->status == 'Aktif')?'bg-label-success':'bg-label-danger' }}">{{ $val->status }}</div>
                    </td>
                    <td class="text-center">
                        @if ($val->submission !== null)
                            @if ($val->submission->grade !== null)
                                {{ $val->submission->grade }}
                            @else
                                <div class="badge bg-label-warning">MENUNGGU PENILAIAN</div>
                            @endif                            
                        @else
                            <div class="badge bg-label-danger">TES BELUM DIKERJAKAN</div>
                        @endif
                    </td>
                    <td>
                        @if ($val->submission !== null)
                            <a href="{{ url('/siswa/materi/submateri/'.Crypt::encrypt($val->id)."/jobsheet") }}" class="btn btn-link btn-sm text-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" data-bs-original-title="Buka Job Sheet">
                                <i class="bx bx-file"></i>
                            </a>
                        @else
                            <a href="{{ url('/siswa/materi/submateri/'.Crypt::encrypt($val->id).'/test') }}" class="btn btn-link btn-sm text-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" data-bs-original-title="Kerjakan Tes">
                                <i class="bx bx-pencil"></i>
                            </a>
                        @endif
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