@extends('templates.main')
@section('title_page', "Jobsheet")

@section('content')
<div class="card">
    <div class="card-body">
        <h2>{{ $materi->main_materi->matkul->kode_matkul }} - {{ $materi->main_materi->matkul->nama_matkul }}</h2>
        <h3>{{ $materi->judul_materi }}</h3>        
    </div>    
</div>
<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-8">
                <h4>Jobsheet</h4>
                <embed 
                src="{{ asset('storage/'.$materi->link_jobsheet) }}#&navpanes=0&toolbar=1" 
                type="application/pdf" 
                width="100%" 
                height="600px" />
            </div>
            <div class="col-12 col-md-4">
                <h4>Video</h4>
                @if($materi->link_video !== null || $materi->link_video != "")
                    <embed 
                    src="{{ $materi->link_video }}"                      
                    width="100%"
                    height="420px"/>
                @else
                    <p class="text-center text-muted">Tidak Ada Video</p>
                @endif
            </div>
        </div>        
    </div>
</div>
@endsection

@section('script')
    
@endsection