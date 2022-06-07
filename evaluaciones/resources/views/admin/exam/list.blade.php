@extends('layouts.admin')

@section('content')
<section>
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">Evaluaciones</h1>

    {{-- <a href="{{ route('admin_create_link') }}" class="btn btn-primary">Crear link de evaluación</a> --}}

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
        </tbody>
    </table>

  </div>
</section>
@endsection

@section('extrajs')
{{-- <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet"> --}}
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.3/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.12.0/dataRender/datetime.js"></script>

<script type="text/javascript">
  $(function () {

    var table = $('.evaluations_table').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.12.0/i18n/es-ES.json',
        },
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin_exam_list') }}",
        columns: [
            {data: 'reference', name: 'reference'},
            {data: 'evaluation.name', name: 'name'},
            {data: 'answered', name: 'answered'},
            {data: 'created_at', name: 'created_at', render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.ssssssZ', 'DD/MM/YYYY HH:mm')},
            {data: 'updated_at', name: 'updated_at', render: $.fn.dataTable.render.moment('YYYY-MM-DDTHH:mm:ss.ssssssZ', 'DD/MM/YYYY HH:mm')},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
    });

  });
</script>
@endsection
