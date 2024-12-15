@extends('templates.main')
@section('title_page', "Buat Kelas Baru")

@section('content')
<div class="card">
    <h5 class="card-header">Buat Kelas Baru</h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <form action="{{ url('master-data/kelas/save_add') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tingkatan</label>
                        <input type="text" class="form-control" name="tingkatan">
                    </div>
                    <button class="btn btn-primary">Simpan</button>
                </form>                
            </div>
        </div>
    </div>
</div>
@endsection