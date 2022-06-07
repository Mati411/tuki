@extends('layouts.admin')

@section('content')
<section>
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">Evaluaciones</h1>

    <a href="{{ route('admin_create_link') }}" class="btn btn-primary">Crear link de evaluación</a>

    <table class="table evaluations_table">
        <thead>
            <tr>
                <th>Referencia</th>
                <th>Evaluación</th>
                <th>Completado?</th>
                <th>Fecha de creación</th>
                <th>Fecha de actualización</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($evaluations as $evaluation)
            <tr>
                <th>{{ $evaluation['reference'] }}</th>
                <th>{{ $evaluation->evaluation->name }}</th>
                <th>{{ $evaluation['answered'] ? 'Sí' : 'No' }}</th>
                <th>{{ $evaluation['created_at'] }}</th>
                <th>{{ $evaluation['updated_at'] }}</th>
                <th>
                    <a class="btn btn-default btn-xs" href="{{ route('admin_exam_report', ['uuid' => $evaluation['guid']]) }}">Ver</a>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>

  </div>
</section>
@endsection

@section('extrajs')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
  $(function () {

    var table = $('.evaluations_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin_exam_list') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

  });
</script>
@endsection
