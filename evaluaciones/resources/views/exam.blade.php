@extends('layouts.default')

@section('content')
<!-- Header -->
<header id="evaluationDescription">
    <div class="container">
        <div class="intro-text col-12 col-md-6" style="padding-top: 250px; padding-bottom: 180px">
            <div class="intro-heading">{{ $exam['title'] }}</div>
            <p style="margin-bottom: 30px;">
              {{ $exam['description'] }}
            </p>
            <a href="javascript:void(0)" class="page-scroll btn btn-xl" onclick="startEvaluation()">Comenzar evaluación</a>
        </div>
    </div>
</header>

<section id="evaluationSection" class="hide">
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">{{ $exam['title'] }}</h1>

    <form method="POST">
        @foreach ($exam['questions'] as $question)
        <div class="row" style="border-bottom: 1px solid #eee; padding: 20px 0">
            <div class="col-sm-6">
                <p>{{ $question['text'] }}</p>
            </div>
            <div class="col-sm-6 text-right">
                <div class="btn-group" data-toggle="buttons">
                    @foreach ($question['options'] as $option)
                    <label class="btn btn-default">
                        <input type="radio" name="question_{{ $question['id'] }}[]" value="{{ $option['value'] }}" required /> {{ $option['text'] }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <div class="text-center" style="margin-top: 40px">
          <button class="btn btn-primary" type="submit">Enviar respuestas</button>
        </div>
    </form>
  </div>
</section>
@endsection
