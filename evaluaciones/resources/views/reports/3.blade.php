@extends('layouts.admin')

@section('content')
<section id="evaluationSection">
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">{{ $exam->evaluation->name }} - {{ $exam->reference }}</h1>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $category => $d)
                    <tr>
                        <td>{{ $category }}</td>
                        <td>{{ $d }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        <th>{{ $total }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="col-md-8">
            <canvas id="chart1" width="700" height="250" style="margin-bottom: 20px"></canvas>
        </div>
    </div>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-12">
            <h3>Conclusiones</h3>

                @foreach ($conclusions as $conclusion)
                <p>{{ $conclusion }}</p>
                @endforeach
        </div>
    </div>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-12">
            <h3>Respuestas</h3>
            <table class="table table-hover">
                <tbody>
                    @foreach ($exam->answers as $answer)
                    <tr>
                        <td>{{ $answer->question->question }}</div>
                        <td>{{ $answer->value }}</div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

  </div>
</section>

{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
const ctx = document.getElementById('chart1').getContext('2d');
const chart1 = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [ {!! $chart_labels !!} ],
        datasets: [{
            label: 'Puntaje',
            data: [ {{ implode(', ', $chart_data['total']) }} ],
            borderColor: '#4f81bd',
            backgroundColor: '#4f81bd',
        },
        {
            label: 'Corte Simulación',
            data: [ {{ implode(', ', $chart_data['cut']) }} ],
            borderColor: '#c0504d',
            backgroundColor: '#c0504d',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
    }
});
</script> --}}

@endsection
