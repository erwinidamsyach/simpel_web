@extends('templates.main')
@section('title_page', "Daftar Materi")

@section('content')
    <div class="row">
        @foreach ($materi as $item)
        <div class="col-12 col-sm-4 col-md-4 gy-3">
            <a href="{{ url('siswa/materi').'/'.Crypt::encrypt($item->id) }}">
                <div class="card border border-primary">
                    <div class="card-body p-3">
                        <p class="mb-0 pb-0 text-secondary">{{ $item->matkul->kode_matkul }}</p>
                        <h5 class="mb-2">{{ $item->matkul->nama_matkul }}</h5>
                        <p class="mb-0 text-secondary">Pemateri : {{ $item->guru->nama_guru }}</p>
                        <hr class="my-2 pb-0">
                        <p class="mb-1 text-secondary">{{ $item->materi_count }} Materi</p>                        
                    </div>
                </div>
            </a>            
        </div>
        @endforeach                
    </div>
@endsection

@section('script')
    
@endsection