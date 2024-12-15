@extends('templates.main')
@section('title_page', "Buat Materi")
@section('content')
<div class="card">
    <h5 class="card-header">Buat Materi</h5>
    <div class="card-body">
        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        <form action="{{ url('/guru/materi/simpan_materi') }}" method="POST">        
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Matkul / Matpel</label>
                        <select name="matkul_id" class="form-control" required>
                            <option value="" selected default>-- Pilih --</option>
                            @foreach ($matkul as $item)
                                <option value="{{ $item->id }}">{{ $item->kode_matkul }} - {{ $item->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select multiple name="kelas[]" class="form-control" required style="height: 160px;">                        
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>            
            </div>
            @csrf
            <button class="btn btn-primary">Simpan & Input Materi</button>
        </form>
    </div>
</div>
@endsection

@section('script')

@endsection