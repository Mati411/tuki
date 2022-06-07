@extends('layouts.admin')

@section('content')
<section>
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">Crear link de evaluación</h1>

    <form method="POST">
        @csrf
        <div class="form-group">
            <label for="selectEvaluation">Tipo de evaluación</label>
            <select name="evaluations_id" class="form-control" id="selectEvaluation" required>
                @foreach ($evaluations as $evaluation)
                    <option value="{{ $evaluation->evaluations_id }}">{{ $evaluation->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="inputReference">Referencia</label>
            <input name="reference" type="text" class="form-control" id="inputReference" required />
            {{-- <p class="help-block">Coloque una referencia que le permita identificar posteriormente la evaluación.</p> --}}
        </div>

        <div class="form-group">
            <label>Sexo</label>
            <div class="radio">
                <label>
                    <input type="radio" name="gender" id="optionsGender" value="MALE" required />
                    Masculino
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="gender" id="optionsGender" value="FEMALE" required />
                    Femenino
                </label>
            </div>
        </div>

        <div class="text-center" style="margin-top: 40px">
          <button class="btn btn-primary" type="submit">Crear link</button>
        </div>
    </form>
  </div>
</section>
@endsection
