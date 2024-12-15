@extends('templates.main')
@section('title_page', 'Test')

@section('content')
<form action="{{ request()->url() }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <h2>Tes</h2>
            <h3>{{ $materi->judul_materi }}</h3>
            <p>Dimulai pukul : {{ \Carbon\Carbon::parse($submission->start_at)->format('d/m/Y H:i:s') }}</p>
            <input type="hidden" name="submission_id" value="{{ $submission->id }}">
        </div>
    </div>
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @foreach ($question_list as $key => $item)
        <div class="card my-4">
            <div class="card-body">
                <p>Pertanyaan ke-{{ $key + 1 }}</p>
                <h4>{{ $item->question }}</h4>
                <input type="hidden" name="question[{{ $item->id }}]">
                <input type="hidden" name="question[{{ $item->id }}][type]" value="{{ $item->question_type }}">                
                @if ($item->question_type == 'essay')
                    <input type="text" name="question[{{ $item->id }}][answer_text]" class="form-control" value="{{ old('question.'.$item->id.'.answer_text') }}">
                @else
                    @foreach ($item->choices as $val)
                        <div class="form-check mt-3">
                            <input type="radio" name="question[{{ $item->id }}][choice_id]" value="{{ $val->id }}" id="{{ $item->id }}_{{ $val->id }}" {{ old("question.{$item->id}.answer") == $val->id ? 'checked' : '' }}>
                            <label for="{{ $item->id }}_{{ $val->id }}" class="form-check-label">{{ $val->choice }}</label>
                        </div>
                    @endforeach
                @endif                
            </div>
        </div>
    @endforeach
    <button type="submit" class="btn btn-primary btn-lg">SELESAI</button>
</form>
@endsection

@section('script')
    
@endsection