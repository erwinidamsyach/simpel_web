@extends('templates.main')
@section('title_page', "Buat Sub Materi")

@section('content')
@if(session('errors'))
    <div class="alert alert-danger">
        {{ session('errors') }}
        {{ session('message') }}
    </div>
@endif
<form action="{{ request()->url() }}" method="POST" enctype="multipart/form-data">
    @csrf    
    <div class="card mb-3">
        <h5 class="card-header">Informasi Umum</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Judul Materi</label>
                        <input type="text" class="form-control" name="judul_materi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengerjaan</label>
                        <div class="row my-0">
                            <div class="col">
                                <input type="date" class="form-control" name="tanggal_mulai" required>
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="tanggal_selesai" required>
                            </div>
                        </div>                    
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label">File Jobsheet</label>
                        <input type="file" class="form-control" name="file" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Video</label>
                        <input type="text" class="form-control" name="link_video" required>
                        <div class="form-text">Pastikan URL video bisa dibuka</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Materi</label>
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="status" class="form-check-input" id="switch-status">
                            <label class="form-check-label" id="txt-status">Tidak Aktif</label>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <h5 class="card-header">Soal Pre Test</h5>            
            </div>
            <div id="area-question" class="mb-3">
            
            </div>
            <button type="button" class="btn btn-success btn-sm" id="add-question-btn">Buat Pertanyaan</button>
        </div>
    </div>
    <hr>
    <div class="clearfix">
        <div class="float-start">
            <button class="btn btn-primary">SIMPAN</button>
        </div>
    </div>
</form>
@endsection


@section('script')
<script>
    let questionCounter = 0;

    // Function to add a new question form
    $('#add-question-btn').click(function () {
        questionCounter++;
        const questionForm = `
            <div class="question-form mb-3" id="question-${questionCounter}">
                <div class="card my-3">
                    <h5 class="card-header">Pertanyaan ke-${questionCounter}</h5>
                    <div class="card-body">
                        <label class="form-label" for="question_text_${questionCounter}">Soal / Pertanyaan</label>
                        <input type="text" class="form-control" id="question_text_${questionCounter}" name="questions[${questionCounter}][text]" required>
                        
                        <label class="form-label" for="question_type_${questionCounter}">Jenis Pertanyaan:</label>
                        <select id="question_type_${questionCounter}" class="form-control question-type" name="questions[${questionCounter}][type]" data-question-id="${questionCounter}">
                            <option value="essay">Essai</option>
                            <option value="multiple_choice">Pilihan Ganda</option>
                        </select>
                        
                        <div class="choices-container" id="choices-container-${questionCounter}" style="display: none;">
                            <hr>
                            <h5>Pilihan Jawaban</h5>
                            <button type="button" class="add-choice-btn btn btn-warning btn-sm" data-question-id="${questionCounter}">Tambahkan Pilihan</button>
                            <div class="choices-list" id="choices-list-${questionCounter}">
                                <!-- Choices will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>                                
            </div>
        `;        
        $('#area-question').append(questionForm);
    });

    // Function to handle question type change
    $(document).on('change', '.question-type', function () {
        const questionId = $(this).data('question-id');
        const selectedType = $(this).val();        
        if (selectedType === 'multiple_choice') {
            $(`#choices-container-${questionId}`).show();
        } else {
            $(`#choices-container-${questionId}`).hide();
            $(`#choices-list-${questionId}`).empty(); // Clear choices if switching away from multiple choice
        }
    });

    // Function to add a new choice to a question
    $(document).on('click', '.add-choice-btn', function () {
        const questionId = $(this).data('question-id');
        const choiceCount = $(`#choices-list-${questionId} .choice-item`).length + 1;

        const choiceForm = `
            <div class="choice-item my-2" id="choice-${questionId}-${choiceCount}">
                <label class="form-label" for="choice_text_${questionId}_${choiceCount}">Pilihan</label>
                <input type="text" class="form-control" id="choice_text_${questionId}_${choiceCount}" name="questions[${questionId}][choices][${choiceCount}][text]" required>
                
                <label for="is_correct_${questionId}_${choiceCount}">Jawaban Benar?</label>
                <input type="checkbox" id="is_correct_${questionId}_${choiceCount}" name="questions[${questionId}][choices][${choiceCount}][is_correct]">
                <button type="button" class="remove-choice-btn btn btn-link text-danger" data-choice-id="${questionId}-${choiceCount}">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;
        $(`#choices-list-${questionId}`).append(choiceForm);
    });

    // Function to remove a choice
    $(document).on('click', '.remove-choice-btn', function () {
        const choiceId = $(this).data('choice-id');
        $(`#choice-${choiceId}`).remove();
    });

    $("#switch-status").on('change', function(evt){
        if(evt.target.checked){
            $("#txt-status").html("Aktif")
        }else{
            $("#txt-status").html("Tidak Aktif")
        }
    });

    function createQuestionForm(){

    }
</script>
@endsection