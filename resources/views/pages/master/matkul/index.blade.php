@extends('templates.main')
@section('title_page', "Master Data Matkul")

@section('content')
<div class="card">
    <h5 class="card-header">Daftar Matkul / Matpel</h5>
    <div class="card-body">
        <div class="clearfix mb-3">
            <div class="float-start">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-matkul">
                    <i class="tf-icons bx bx-plus"></i> Tambah Data Matkul
                </button>
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
                    <th>Kode</th>
                    <th>Nama Matkul / Matpel</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_matkul as $item)
                    <tr>
                        <td>{{ $item->kode_matkul }}</td>
                        <td>{{ $item->nama_matkul }}</td>
                        <td>{{ $item->status }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="modal-matkul">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ url('master-data/matkul/save_add') }}" id="form" method="POST">
                    @csrf
                    <div class="mb-3 form-absen">
                        <label class="form-label">Kode Matkul / Matpel</label>
                        <input type="text" class="form-control" name="kode_matkul" required="required">
                    </div>                    
                    <div class="mb-3">
                        <label class="form-label">Nama Matkul / Matpel</label>
                        <input type="text" class="form-control" name="nama_matkul" required="required">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan">
                    </div>
                    <button class="btn btn-primary" id="btn-save-guru">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    
@endsection