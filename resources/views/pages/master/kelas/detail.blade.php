@extends('templates.main')
@section('title_page', 'Detail Kelas')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <h5 class="card-header">Detail Kelas</h5>
            <div class="card-body">
                <form action="{{ url('master-data/kelas/save_edit/'.$enc_id) }}" id="form-edit" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" value="{{ $kelas->nama_kelas }}" readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tingkatan</label>
                        <input type="text" class="form-control" name="tingkatan" value="{{ $kelas->tingkatan }}" readonly>
                    </div>
                    <button class="btn btn-primary d-none" id="btn-simpan">Simpan</button>
                    <button class="btn btn-warning" type="button" id="btn-edit">Edit</button>
                </form>        
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-4">
            <h5 class="card-header">Siswa</h5>
            <div class="card-body">
                <div class="clearfix mb-3">
                    <div class="float-start">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-siswa">
                            <i class="tf-icons bx bx-plus"></i> Tambah Siswa
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
                            <th width="15%">No. Absen</th>
                            <th width="20%">No. Induk</th>
                            <th width="30%">Nama Siswa</th>
                            <th width="20%">Jenis Kelamin</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas->siswa as $item)
                            <tr>
                                <td>{{ $item->no_absen }}</td>
                                <td>{{ $item->no_induk }}</td>
                                <td>{{ $item->nama_siswa }}</td>
                                <td>{{ $item->jenis_kelamin }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-link text-muted px-0" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><button class="dropdown-item" onclick="editSiswa('{{ $item }}')">Edit Data</a></li>
                                            <li><a href="{{ url(Request::path()."/reset-password-siswa")."/".Crypt::encrypt($item->id) }}" class="dropdown-item">Reset Password</a></li>
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
<div class="modal fade" id="modal-siswa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Tambah Data Siswa</h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('master-data/kelas/add_siswa/'.$enc_id) }}" id="form" method="POST">
                    @csrf
                    <div class="mb-3 form-absen">
                        <label class="form-label">No. Absen</label>
                        <input type="number" min="1" max="99" step="1" class="form-control" name="no_absen" required="required">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Induk</label>
                        <input type="text" class="form-control" name="no_induk" required="required">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_siswa" required="required">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-Laki">Laki - Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" id="btn-save-siswa">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("#btn-edit").on('click', function(){
        $("#form-edit input.form-control").attr('readonly', false);
        $("#btn-simpan").removeClass('d-none');
    });

    $("input[name='no_absen']").on('keyup', function(){        
        var val = $(this).val();
        $.ajax({
            type: "GET",
            url : "{{ url('master-data/kelas/ajax_cek_absen') }}",
            data : {
                "id_kelas" : "{{ $enc_id }}",
                "no_absen" : val,
            },
            success:function(resp){
                if(resp['error'] !== undefined && !resp['error']){                    
                    if(resp['data']['exist']){                        
                        $(".form-absen label").addClass('text-danger');
                        $(".form-absen input").addClass('border border-danger');
                        $("#btn-save-siswa").attr('disabled',true);
                    }else{
                        $(".form-absen label").removeClass('text-danger');
                        $(".form-absen input").removeClass('border border-danger');
                        $("#btn-save-siswa").attr('disabled',false);
                    }
                }
            }
        })
    });

    function editSiswa(item){
        var item = JSON.parse(item);
        
        setTimeout(() => {
            $("input[name='no_absen']").val(item.no_absen);
            $("input[name='no_induk']").val(item.no_induk);
            $("input[name='nama_siswa']").val(item.nama_siswa);
            $("select>option[value='"+item.jenis_kelamin+"']").attr('selected', true);
            $("#form").attr('action', "{{ url('master-data/kelas/edit_siswa/'.$enc_id).'/' }}"+item.id);
        }, 400);        

        $("#modal-siswa").modal('show');
    }

    $("#modal-siswa").on('show.bs.modal', function(){
        $("input[name='no_absen']").val("");
        $("input[name='no_induk']").val("");
        $("input[name='nama_siswa']").val("");
        $("#form").attr('action', "{{ url('master-data/kelas/add_siswa/'.$enc_id) }}");
    })
</script>
@endsection