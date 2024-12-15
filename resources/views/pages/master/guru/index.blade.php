@extends('templates.main')
@section('title_page', "Master Data Guru")

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <h5 class="card-header">Daftar Guru</h5>
            <div class="card-body">
                <div class="clearfix mb-3">
                    <div class="float-start">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-guru">
                            <i class="tf-icons bx bx-plus"></i> Tambah Guru
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
                            <th>No. Registrasi</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Matkul / Matpel</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_guru as $index => $item)
                            <tr>
                                <td>{{ $item->no_registrasi }}</td>
                                <td>{{ $item->nama_guru }}</td>
                                <td>{{ $item->jenis_kelamin }}</td>
                                <td>
                                    {{ $item->matkul[0]->kode_matkul }}<br/>
                                    {{ $item->matkul[0]->nama_matkul }}
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-link text-muted px-0" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><button class="dropdown-item" onclick="editGuru('{{ $index }}', '{{ $item }}')">Edit Data</button></li>
                                            <li><a href="{{ url(Request::path()."/reset-password-guru")."/".Crypt::encrypt($item->id) }}" class="dropdown-item">Reset Password</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
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
    </div>
</div>
<div class="modal fade" id="modal-guru">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Tambah Data Guru</h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('master-data/guru/save_add') }}" id="form" method="POST">
                    @csrf
                    <div class="mb-3 form-absen">
                        <label class="form-label">No. Registrasi / NUPTK</label>
                        <input type="number" min="1" step="1" class="form-control" name="no_registrasi" required="required">
                    </div>                    
                    <div class="mb-3">
                        <label class="form-label">Nama Guru</label>
                        <input type="text" class="form-control" name="nama_guru" required="required">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-Laki">Laki - Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Matkul / Matpel Yang Diampu</label>
                        <select name="id_matkul" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($list_matkul as $item)
                                <option value="{{ $item->id }}">{{ $item->kode_matkul }} - {{ $item->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary" id="btn-save-guru">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function editGuru(idx, item){
        var item = JSON.parse(item);        
        $("input[name='no_registrasi']").val(item.no_registrasi);
        $("input[name='nama_guru']").val(item.nama_guru);
        $("select>option[value='"+item.jenis_kelamin+"']").attr('selected', true);
        $("select>option[value='"+item.id_matkul+"']").attr('selected', true);
        $("#form").attr('action', "{{ url('master-data/guru/save_edit').'/' }}"+item.id);

        $("#modal-guru").modal('show');
    }
</script>
@endsection