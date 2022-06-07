@extends('layouts.default')

@section('content')
<!-- Header -->
<header id="evaluationDescription">
    <div class="container">
        <div class="intro-text col-12 col-md-6" style="padding-top: 250px; padding-bottom: 180px">
            <div class="intro-heading">{{ $exam->evaluation->name }}</div>
            <p style="margin-bottom: 30px;">
             {{ $exam->evaluation->description }}
            </p>
            <a href="javascript:void(0)" class="page-scroll btn btn-xl" onclick="startEvaluation()">Comenzar evaluación</a>
        </div>
    </div>
</header>

<section id="evaluationSection" class="hide">
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">{{ $exam['title'] }}</h1>

    <form method="POST">
        @csrf
        @foreach ($exam->evaluation->questions as $question)
        <div class="row" style="border-bottom: 1px solid #eee; padding: 20px 0">
            <div class="col-sm-6">
                <p>{{ $question['question'] }}</p>
            </div>
            <div class="col-sm-6 text-right">
                @if ($question->type == 1)
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default {{ $question->default_value == '0' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="0" required {{ $question->default_value == '0' ? 'checked' : '' }} /> Nada
                    </label>
                    <label class="btn btn-default {{ $question->default_value == '1' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="1" required {{ $question->default_value == '1' ? 'checked' : '' }} /> Poco
                    </label>
                    <label class="btn btn-default {{ $question->default_value == '2' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="2" required {{ $question->default_value == '2' ? 'checked' : '' }} /> Moderado
                    </label>
                    <label class="btn btn-default {{ $question->default_value == '3' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="3" required {{ $question->default_value == '3' ? 'checked' : '' }} /> Bastante
                    </label>
                    <label class="btn btn-default {{ $question->default_value == '4' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="4" required {{ $question->default_value == '4' ? 'checked' : '' }} /> Mucho
                    </label>
                </div>
                @endif
                @if ($question->type == 2)
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default {{ $question->default_value == '0' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="{{ $question->inverse ? '0' : '1' }}" required {{ $question->default_value == '0' ? 'checked' : '' }} /> Verdadero
                    </label>
                    <label class="btn btn-default {{ $question->default_value == '1' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="{{ $question->inverse ? '1' : '0' }}" required {{ $question->default_value == '1' ? 'checked' : '' }} /> Falso
                    </label>
                </div>
                @endif
                @if ($question->type == 3)
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default {{ $question->default_value == '0' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="{{ $question->inverse ? '0' : '1' }}" required {{ $question->default_value == '0' ? 'checked' : '' }} /> SI
                    </label>
                    <label class="btn btn-default {{ $question->default_value == '1' ? 'active' : '' }}">
                        <input type="radio" name="answers[{{ $question['evaluations_questions_id'] }}]" value="{{ $question->inverse ? '1' : '0' }}" required checked /> NO
                    </label>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <div class="text-center" style="margin-top: 40px">
          <button class="btn btn-primary" type="submit">Enviar respuestas</button>
        </div>
    </form>
  </div>
</section>

<script>
  function startEvaluation () {
    document.getElementById('evaluationDescription').classList.add('hide');
    document.getElementById('evaluationSection').classList.remove('hide');
  };

  var respuesta = $('#respuestas1 > div');
  for (var i = 0; i < 6; i++) {
    respuesta.clone().appendTo('#respuestas1');
  }
</script>
@endsection
