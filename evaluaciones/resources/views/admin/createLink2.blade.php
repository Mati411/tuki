@extends('layouts.admin')

@section('content')
<section>
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">Crear link de evaluación</h1>

    <input class="form-control" type="text" value="{{ route('exam_form', ['uuid' => $exam->guid]) }}" />

    <img src="https://api.qrserver.com/v1/create-qr-code/?size=256x256&data={{ route('exam_form', ['uuid' => $exam->guid]) }}" alt="QR" />
  </div>
</section>
@endsection
