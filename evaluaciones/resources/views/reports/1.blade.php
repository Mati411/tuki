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
                        <th>Total</th>
                        <th>Contestadas</th>
                        <th>Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sum as $category => $data)
                    <tr>
                        <td>{{ $category }}</td>
                        <td>{{ $data['total'] }}</td>
                        <td>{{ $data['answered'] }}</td>
                        <td>{{ $data['average'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table table-hover">
                {{-- <tbody> --}}
                    @foreach ($table1 as $k => $v)
                    <tr>
                        <th>{{ $k }}</th>
                        <td>{{ $v }}</td>
                    </tr>
                    @endforeach
                {{-- </tbody> --}}
            </table>
        </div>

        <div class="col-md-8">
            <canvas id="chart1" width="700" height="250" style="margin-bottom: 20px"></canvas>
        </div>
    </div>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-12">
            <h3>Conclusiones</h3>
            <ul>
                @foreach ($conclusions as $conclusion)
                <li>{{ $conclusion }}</li>
                @endforeach
            </ul>
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
                        <td>{{ ['Nada','Muy Poco', 'Poco', 'Bastante', 'Mucho'][$answer->value] }}</div>
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
    type: 'line',
    data: {
        labels: [ {!! $chart_labels !!} ],
        datasets: [{
            label: 'Media',
            data: [ {{ implode(', ', $chart_data['median']) }} ],
            borderColor: '#666699',
            backgroundColor: '#666699',
        },
        {
            label: 'Desvio',
            data: [ {{ implode(', ', $chart_data['deviation']) }} ],
            borderColor: '#993366',
            backgroundColor: '#993366',
        },
        {
            label: '{{ $exam->gender == 'FEMALE' ? 'Empleada' : 'Empleado' }}',
            data: [ {{ implode(', ', $chart_data['employee']) }} ],
            borderColor: '#99CC00',
            backgroundColor: '#99CC00',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
    }
});
</script>

@endsection
