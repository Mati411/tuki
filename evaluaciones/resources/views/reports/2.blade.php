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
                        <th></th>
                        <th>Puntaje</th>
                        <th>Corte Simulación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $category => $d)
                    <tr>
                        <td>{{ $category }}</td>
                        <td>{{ $d['total'] }}</td>
                        <td>{{ $d['cut'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-8">
            <canvas id="chart1" width="700" height="250" style="margin-bottom: 20px"></canvas>
        </div>
    </div>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-12">
            <h3>Resultado</h3>
            @if ($total >= 16)
            <p>El resultado total de {{ $total }} puntos (punto de corte propuesto por los autores 16 puntos, con un grado de sensibilidad del 96% al 98%) indica que el/la evaluado/a <strong>simula</strong> sintomatología neuropsiquiátrica.</p>
            <p>
                De acuerdo a las escalas administradas, se observan signos de simulación o
                sobresimulación de padecimiento de síntomas psiquiátricos y/o neurocognitivos,
                <strong>Utilidad de los inventarios SCL-90-R y SIMS en la detección de simulación de
                trastornos mentales en el entorno laboral. Bertone, M. Loskin, U. Lopez
                Regueira, J. VERTEX Rev. Arg. de Psiquiat. 2017, Vol. XXIX: 85-90.</strong>
            </p>
            @else
            <p>El resultado total de {{ $total }} puntos (punto de corte propuesto por los autores 16 puntos, con un grado de sensibilidad del 96% al 98%) indica que el/la evaluado/a <strong>NO simula</strong> sintomatología neuropsiquiátrica.</p>
            @endif
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
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
</script>

@endsection
