<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\PatientsEvaluations;
use App\Evaluations;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(Request $request, $uuid)
    {
        $exam = PatientsEvaluations::where('guid', $uuid)->first();

        if (! $exam)
            return view('exam.invalid');

        return view('reports.'.$exam->evaluations_id, $this->{'report'.$exam->evaluations_id}($exam));
    }

    /**
     * SCL-90R
     */
    public function report1(PatientsEvaluations $exam)
    {
        $data = [];
        $total_en_cero = 0;

        foreach ($exam->answers as $answer) {
            $category = $answer->question->category;

            if (! isset($data[$category])) {
                $data[$category] = [
                    'total' => 0,
                    'answered' => 0,
                    'average' => 0,
                ];
            }

            if (intval($answer->value) == 0) {
                $total_en_cero++;
            }

            $data[$category]['total'] += intval($answer->value);
            $data[$category]['answered'] += 1;
            $data[$category]['average'] = round($data[$category]['total'] / $data[$category]['answered'], 2);
        }

        // Los items adicionales no se promedian
        $data['Items Adicionales']['average'] = 0;

        // Suma de promedios
        $total_promedio = array_sum(array_map(function ($v) { return $v['average']; }, $data));
        $total_total = array_sum(array_map(function ($v) { return $v['total']; }, $data));
        $total_respondidas = array_sum(array_map(function ($v) { return $v['answered']; }, $data));

        $table1 = [
            'IGS' => $total_respondidas > 0 ? round($total_promedio / $total_respondidas, 2) : 0,
            'TSP' => 90 - $total_en_cero,
            'IMPS' => $total_total / (90 - $total_en_cero),
        ];

        // Constantes
        $avg = [
            'MALE' => [
                'Somatizaciones' => [
                    'median' => 0.57,
                    'deviation' => 1.5,
                ],
                'Obsesiones' => [
                    'median' => 1,
                    'deviation' => 1.69,
                ],
                'Sens. Interper' => [
                    'median' => 0.69,
                    'deviation' => 1.28,
                ],
                'Depresión' => [
                    'median' => 0.81,
                    'deviation' => 1.4,
                ],
                'Ansiedad' => [
                    'median' => 0.74,
                    'deviation' => 1.3,
                ],
                'Hostilidad' => [
                    'median' => 0.78,
                    'deviation' => 1.43,
                ],
                'Ansiedad Fóbica' => [
                    'median' => 0.29,
                    'deviation' => 0.68,
                ],
                'Paranoia' => [
                    'median' => 0.85,
                    'deviation' => 1.56,
                ],
                'Psicoticismo' => [
                    'median' => 0.46,
                    'deviation' => 0.93,
                ],
            ],
            'FEMALE' => [
                'Somatizaciones' => [
                    'median' => 0.85,
                    'deviation' => 1.47,
                ],
                'Obsesiones' => [
                    'median' => 1.12,
                    'deviation' => 1.82,
                ],
                'Sens. Interper' => [
                    'median' => 0.85,
                    'deviation' => 1.48,
                ],
                'Depresión' => [
                    'median' => 1.05,
                    'deviation' => 1.74,
                ],
                'Ansiedad' => [
                    'median' => 0.96,
                    'deviation' => 1.6,
                ],
                'Hostilidad' => [
                    'median' => 0.8,
                    'deviation' => 1.46,
                ],
                'Ansiedad Fóbica' => [
                    'median' => 0.41,
                    'deviation' => 0.92,
                ],
                'Paranoia' => [
                    'median' => 0.9,
                    'deviation' => 1.68,
                ],
                'Psicoticismo' => [
                    'median' => 0.52,
                    'deviation' => 1.01,
                ],
            ],
        ];

        $csl = [
            [
                'category' => 'Somatizaciones',
                'text' => 'Presenta malestares que la persona percibe relacionados con diferentes disfunciones corporales (cardiovasculares, gastrointestinales, respiratorios).',
                'threshold' => [
                    'MALE' => 2.07,
                    'FEMALE' => 2.32,
                ],
            ],
            [
                'category' => 'Obsesiones',
                'text' => 'Puntúa elevado para obsesiones. Precencia de pensamientos, acciones e impulsos que son vivenciados como imposibles de evitar o no deseados.',
                'threshold' => [
                    'MALE' => 2.69,
                    'FEMALE' => 2.94,
                ],
            ],
            [
                'category' => 'Sens. Interper',
                'text' => 'Presencia de síntomas de sensibilidad interpersonal, se focaliza en detectar la presencia de sentimientos de inferioridad e inadecuación, en especial cuando la persona se compara con sus semejantes.',
                'threshold' => [
                    'MALE' => 1.97,
                    'FEMALE' => 2.33,
                ],
            ],
            [
                'category' => 'Depresión',
                'text' => 'Puntúa elevado para depresión: Estado de ánimo disfórico, falta de motivación, poca energía vital, sentimientos de desesperanza, ideaciones suicidas.',
                'threshold' => [
                    'MALE' => 2.21,
                    'FEMALE' => 2.79,
                ],
            ],
            [
                'category' => 'Ansiedad',
                'text' => 'Presenta síntomas de ansiedad, tales como nerviosismo, tensión, ataques de pánico, miedos.',
                'threshold' => [
                    'MALE' => 2.04,
                    'FEMALE' => 2.56,
                ],
            ],
            [
                'category' => 'Hostilidad',
                'text' => 'Existe un aumento en las respuesta del ítem hostilidad. Esta dimensión hace referencia a pensamientos, sentimientos y acciones característicos de la presencia de afectos negativos de enojo.',
                'threshold' => [
                    'MALE' => 2.21,
                    'FEMALE' => 2.26,
                ],
            ],
            [
                'category' => 'Ansiedad Fóbica',
                'text' => 'Ansiedad Fóbica: Este malestar alude a una respuesta persistente de miedo (a personas específicas, lugares, objetos, situaciones) que es en sí misma irracional y desproporcionada en relación con el estímulo que la provoca.',
                'threshold' => [
                    'MALE' => 0.97,
                    'FEMALE' => 1.33,
                ],
            ],
            [
                'category' => 'Paranoia',
                'text' => 'Presenta síntomas de ideación paranoide y comportamientos paranoides fundamentalmente en tanto desórdenes del pensamiento: pensamiento proyectivo, suspicacia, temor a la pérdida de autonomía.',
                'threshold' => [
                    'MALE' => 2.41,
                    'FEMALE' => 2.58,
                ],
            ],
            [
                'category' => 'Psicoticismo',
                'text' => 'Indica presencia de síntomas referidos a estados de soledad, estilo de vida esquizoide, alucinaciones y control del pensamiento.',
                'threshold' => [
                    'MALE' => 1.39,
                    'FEMALE' => 1.53,
                ],
            ],
        ];

        $csl2 = [
            'TSP' => [
                'text' => 'El valor de la escala Total de Síntomas Positivos indica tendencia a exagerar sus patologías.',
                'threshold' => [
                    'MALE' => 50.87,
                    'FEMALE' => 55.49,
                ],
            ],
            'IMPS' => [
                'text' => 'Las respuestas brindadas para este ítem sugieren patrones de respuestas que deben analizarse en términos de actitudes de fingimiento.',
                'threshold' => [
                    'MALE' => 2.26,
                    'FEMALE' => 2.38,
                ],
            ],
        ];

        $chart_labels = "'".implode("', '", array_keys($avg[$exam->gender]))."'";
        $chart_data = [
            'median' => [],
            'deviation' => [],
            'employee' => [],
        ];

        foreach ($avg[$exam->gender] as $category => $item) {
            $chart_data['median'][] = $item['median'];
            $chart_data['deviation'][] = $item['deviation'];
            $chart_data['employee'][] = $data[$category]['average'];
        }

        // Conclusiones
        $conclusions = [];
        $total_sintomas_positivos = array_sum(array_map(function ($v) { return $v['total']; }, $data));

        foreach ($csl as $item) {
            if ($data[$item['category']]['average'] > $item['threshold'][$exam->gender]) {
                $conclusions[] = $item['text'];
            }
        }

        foreach ($csl2 as $key => $item) {
            if ($table1[$key] > $csl2[$key]['threshold'][$exam->gender]) {
                $conclusions[] = $csl2[$key]['text'];
            }
        }

        return [
            'exam' => $exam,
            'sum' => $data,
            'avg' => $avg,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_data,
            'table1' => $table1,
            'conclusions' => $conclusions,
        ];
    }

    /**
     * SIMS (Escala de sintomas III)
     */
    public function report2(PatientsEvaluations $exam)
    {
        $data = [
            'Amnésia' => [
                'total' => 0,
                'cut' => 3,
            ],
            'Bajo CI' => [
                'total' => 0,
                'cut' => 3,
            ],
            'Deterioro Neurológico' => [
                'total' => 0,
                'cut' => 3
            ],
            'Psicosis' => [
                'total' => 0,
                'cut' => 2,
            ],
            'Afectividad' => [
                'total' => 0,
                'cut' => 3,
            ],
        ];

        $total = 0;

        foreach ($exam->answers as $answer) {
            $data[$answer->question->category]['total'] += intval($answer->value);
            $total += intval($answer->value);
        }

        $chart_labels = "'".implode("', '", array_keys($data))."'";
        $chart_data = [
            'total' => [],
            'cut' => [],
        ];

        foreach ($data as $category => $d) {
            $chart_data['total'][] = $d['total'];
            $chart_data['cut'][] = $d['cut'];
        }

        return [
            'exam' => $exam,
            'data' => $data,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_data,
            'total' => $total,
        ];
    }

    /**
     * SCID II - Cuestionario de personalidad
     */
    public function report3(PatientsEvaluations $exam)
    {
        $data = [];
        $conclusions = [];
        $total = 0;

        foreach ($exam->answers as $answer) {
            if (! isset($data[$answer->question->category])) {
                $data[$answer->question->category] = 0;
            }
            $data[$answer->question->category] += intval($answer->value);
            $total += intval($answer->value);
        }

        if ($data['Evitativo'] >= 3 && $data['Evitativo'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad evitativa. La característica esencial del trastorno de la personalidad por evitación es un patrón general de inhibición social, unos sentimientos de inadecuación y una hipersensibilidad a la evaluación negativa que comienzan al principio de la edad adulta y que se dan en diversos contextos.
Los sujetos con trastorno de la personalidad por evitación evitan trabajos o actividades escolares que impliquen un contacto interpersonal importante, porque tienen miedo de las críticas, la desaprobación o el rechazo. Pueden declinar las ofertas de promoción laboral debido a que las nuevas responsabilidades ocasionarían críticas de los compañeros. Estos individuos evitan hacer nuevos amigos a no ser que estén seguros de que van a ser apreciados y aceptados sin críticas. Hasta que no superan pruebas muy exigentes que demuestren lo contrario, se considera que los demás son críticos y les rechazan. Las personas con este trastorno no participan en actividades de grupo a no ser que reciban ofertas repetidas y generosas de apoyo y protección. La intimidad personal suele ser difícil para ellos, aunque son capaces de establecer relaciones íntimas cuando hay seguridad de una aceptación acrítica. Pueden actuar con represión, tener dificultades para hablar de sí mismos y tener sentimientos íntimos de temor a ser comprometidos, ridiculizados o avergonzados.
EOF;
        } elseif ($data['Evitativo'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad evitativa. La característica esencial del trastorno de la personalidad por evitación es un patrón general de inhibición social, unos sentimientos de inadecuación y una hipersensibilidad a la evaluación negativa que comienzan al principio de la edad adulta y que se dan en diversos contextos.
Los sujetos con trastorno de la personalidad por evitación evitan trabajos o actividades escolares que impliquen un contacto interpersonal importante, porque tienen miedo de las críticas, la desaprobación o el rechazo. Pueden declinar las ofertas de promoción laboral debido a que las nuevas responsabilidades ocasionarían críticas de los compañeros. Estos individuos evitan hacer nuevos amigos a no ser que estén seguros de que van a ser apreciados y aceptados sin críticas. Hasta que no superan pruebas muy exigentes que demuestren lo contrario, se considera que los demás son críticos y les rechazan. Las personas con este trastorno no participan en actividades de grupo a no ser que reciban ofertas repetidas y generosas de apoyo y protección. La intimidad personal suele ser difícil para ellos, aunque son capaces de establecer relaciones íntimas cuando hay seguridad de una aceptación acrítica. Pueden actuar con represión, tener dificultades para hablar de sí mismos y tener sentimientos íntimos de temor a ser comprometidos, ridiculizados o avergonzados.
EOF;
        }

        if ($data['Dependencia'] >= 4 && $data['Dependencia'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad dependiente. La característica esencial del trastorno de la personalidad por dependencia es una necesidad general y excesiva de que se ocupen de uno, que ocasiona un comportamiento de sumisión y adhesión y temores de separación. Este patrón empieza al principio de la edad adulta y se da en diversos contextos. Los comportamientos dependientes y sumisos están destinados a provocar atenciones y surgen de una percepción de uno mismo como incapaz de funcionar adecuadamente sin la ayuda de los demás.
Los sujetos con trastorno de la personalidad por dependencia tienen grandes dificultades para tomar las decisiones cotidianas (por ejemplo, qué color de camisa escoger para ir a trabajar o si llevar paraguas o no), si no cuentan con un excesivo aconsejamiento y reafirmación por parte de los demás. Estos individuos tienden a ser pasivos y a permitir que los demás (frecuentemente una sola persona) tomen las iniciativas y asuman la responsabilidad en las principales parcelas de su vida. Es típico que los adultos con este trastorno dependan de un progenitor o del cónyuge para decidir dónde deben vivir, qué tipo de trabajo han de tener y de quién tienen que ser amigos. Los adolescentes con este trastorno permitirán que sus padres decidan qué ropa ponerse, con quién tienen que ir, cómo tienen que emplear su tiempo libre y a qué escuela o colegio han de ir. Esta necesidad de que los demás asuman las responsabilidades va más allá de lo que es apropiado para la edad o para la situación en cuanto a pedir ayuda a los demás (por ejemplo, las necesidades específicas de los niños, las personas mayores y los minusválidos). El trastorno de la personalidad por dependencia puede darse en un sujeto con una enfermedad médica o una incapacidad grave, pero en estos casos la dificultad para asumir responsabilidades debe ir más lejos de lo que normalmente se asocia a esa enfermedad o incapacidad.
EOF;
        } elseif ($data['Dependencia'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad dependiente. La característica esencial del trastorno de la personalidad por dependencia es una necesidad general y excesiva de que se ocupen de uno, que ocasiona un comportamiento de sumisión y adhesión y temores de separación. Este patrón empieza al principio de la edad adulta y se da en diversos contextos. Los comportamientos dependientes y sumisos están destinados a provocar atenciones y surgen de una percepción de uno mismo como incapaz de funcionar adecuadamente sin la ayuda de los demás.
Los sujetos con trastorno de la personalidad por dependencia tienen grandes dificultades para tomar las decisiones cotidianas (por ejemplo, qué color de camisa escoger para ir a trabajar o si llevar paraguas o no), si no cuentan con un excesivo aconsejamiento y reafirmación por parte de los demás. Estos individuos tienden a ser pasivos y a permitir que los demás (frecuentemente una sola persona) tomen las iniciativas y asuman la responsabilidad en las principales parcelas de su vida. Es típico que los adultos con este trastorno dependan de un progenitor o del cónyuge para decidir dónde deben vivir, qué tipo de trabajo han de tener y de quién tienen que ser amigos. Los adolescentes con este trastorno permitirán que sus padres decidan qué ropa ponerse, con quién tienen que ir, cómo tienen que emplear su tiempo libre y a qué escuela o colegio han de ir. Esta necesidad de que los demás asuman las responsabilidades va más allá de lo que es apropiado para la edad o para la situación en cuanto a pedir ayuda a los demás (por ejemplo, las necesidades específicas de los niños, las personas mayores y los minusválidos). El trastorno de la personalidad por dependencia puede darse en un sujeto con una enfermedad médica o una incapacidad grave, pero en estos casos la dificultad para asumir responsabilidades debe ir más lejos de lo que normalmente se asocia a esa enfermedad o incapacidad.
EOF;
        }

        if ($data['Obsesivo'] >= 3 && $data['Obsesivo'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad obsesiva. La característica esencial del trastorno obsesivo-compulsivo de la personalidad es una preocupación por el orden, el perfeccionismo y el control mental e interpersonal, a expensas de la flexibilidad, la espontaneidad y la eficiencia. Este patrón empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno obsesivo-compulsivo de la personalidad intentan mantener la sensación de control mediante una atención esmerada a las reglas, los detalles triviales, los protocolos, las listas, los horarios o las formalidades hasta el punto de perder de vista el objetivo principal de la actividad. Son excesivamente cuidadosos y propensos a las repeticiones, a prestar una atención extraordinaria a los detalles y a comprobar repetidamente los posibles errores. No son conscientes del hecho de que las demás personas acostumbran a enfadarse por los retrasos y los inconvenientes que derivan de ese comportamiento. Por ejemplo, cuando estos individuos pierden una lista de las cosas que hay que hacer, son capaces de invertir una gran cantidad de tiempo buscándola, en lugar de emplear unos minutos en volver a confeccionarla de memoria y ponerse a hacer las tareas de que se trate. El tiempo se distribuye mal y las tareas más importantes se dejan para el último momento. El perfeccionismo y los altos niveles de rendimiento que se autoimponen causan a estos sujetos una disfunción y un malestar significativos. Pueden estar tan interesados en llevar a cabo con absoluta perfección cualquier detalle de un proyecto, que éste no se acabe nunca. Por ejemplo, se retrasa la finalización de un informe escrito debido al tiempo que se pierde en reescribirlo repetidas veces hasta que todo quede prácticamente «perfecto». Los objetivos se pierden y los aspectos que no constituyen el objetivo actual de la actividad pueden caer en el desorden.
EOF;
        } elseif ($data['Obsesivo'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad obsesiva. La característica esencial del trastorno obsesivo-compulsivo de la personalidad es una preocupación por el orden, el perfeccionismo y el control mental e interpersonal, a expensas de la flexibilidad, la espontaneidad y la eficiencia. Este patrón empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno obsesivo-compulsivo de la personalidad intentan mantener la sensación de control mediante una atención esmerada a las reglas, los detalles triviales, los protocolos, las listas, los horarios o las formalidades hasta el punto de perder de vista el objetivo principal de la actividad. Son excesivamente cuidadosos y propensos a las repeticiones, a prestar una atención extraordinaria a los detalles y a comprobar repetidamente los posibles errores. No son conscientes del hecho de que las demás personas acostumbran a enfadarse por los retrasos y los inconvenientes que derivan de ese comportamiento. Por ejemplo, cuando estos individuos pierden una lista de las cosas que hay que hacer, son capaces de invertir una gran cantidad de tiempo buscándola, en lugar de emplear unos minutos en volver a confeccionarla de memoria y ponerse a hacer las tareas de que se trate. El tiempo se distribuye mal y las tareas más importantes se dejan para el último momento. El perfeccionismo y los altos niveles de rendimiento que se autoimponen causan a estos sujetos una disfunción y un malestar significativos. Pueden estar tan interesados en llevar a cabo con absoluta perfección cualquier detalle de un proyecto, que éste no se acabe nunca. Por ejemplo, se retrasa la finalización de un informe escrito debido al tiempo que se pierde en reescribirlo repetidas veces hasta que todo quede prácticamente «perfecto». Los objetivos se pierden y los aspectos que no constituyen el objetivo actual de la actividad pueden caer en el desorden.
EOF;
        }

        if ($data['Pasivo-Agresivo'] >= 3 && $data['Pasivo-Agresivo'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad pasivo-agresiva. El trastorno pasivo-agresivo de la personalidad también llamado negativista se caracteriza por un patrón de actitudes de oposición y resistencia pasiva ante las demandas de una adecuada actuación en situaciones sociales y laborales. la personalidad pasivo-agresiva expresa continuas quejas sobre su desgracia y de sentirse incomprendido y despreciado por demás. Menosprecia y critica de forma poco razonable a las figuras de autoridad con quien establece relaciones de hostilidad encubierta pero de dependencia. Muestra envidia y resentimiento hacia el éxito ajeno y recurre a continuos intentos para frustrar los planes de los otros. Es frecuente la búsqueda continua de defectos en aquellas personas de las que dependen. A menudo la persona oscila entre las amenazas hostiles y el arrepentimiento. Es común que presente rápidas sucesiones de estados de ánimo e inestabilidad emocional. Son irritables y con baja tolerancia a la frustración.
EOF;
        } elseif ($data['Pasivo-Agresivo'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad pasivo-agresiva. El trastorno pasivo-agresivo de la personalidad también llamado negativista se caracteriza por un patrón de actitudes de oposición y resistencia pasiva ante las demandas de una adecuada actuación en situaciones sociales y laborales. la personalidad pasivo-agresiva expresa continuas quejas sobre su desgracia y de sentirse incomprendido y despreciado por demás. Menosprecia y critica de forma poco razonable a las figuras de autoridad con quien establece relaciones de hostilidad encubierta pero de dependencia. Muestra envidia y resentimiento hacia el éxito ajeno y recurre a continuos intentos para frustrar los planes de los otros. Es frecuente la búsqueda continua de defectos en aquellas personas de las que dependen. A menudo la persona oscila entre las amenazas hostiles y el arrepentimiento. Es común que presente rápidas sucesiones de estados de ánimo e inestabilidad emocional. Son irritables y con baja tolerancia a la frustración.
EOF;
        }

        if ($data['Depresivo'] >= 4 && $data['Depresivo'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad depresiva. El trastorno depresivo de la personalidad se caracteriza por un patrón de comportamiento depresivo crónico que manifiesta algunos de los siguientes síntomas: -Estado de ánimo caracterizado por abatimiento, tristeza, desánimo, desilusión e infelicidad -Autoconcepto basado en creencias de inadecuación, inutilidad y baja autoestima -Autocrítica y menosprecio -Preocupaciones -Lleva la contraria, critica y juzga a los demás -pesimismo -Culpabilidad y remordimiento. La personalidad depresiva se caracteriza por la incapacidad para relajarse y disfrutar, la seriedad y la falta de sentido del humor. Físicamente reflejan su estado de ánimo, presentando una postura hundida, ve vacía, expresión facial deprimida y retraso psicomotor. Les sus expectativas para el futuro son negativas y no creen que las cosas puedan mejorar.
EOF;
        } elseif ($data['Depresivo'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad depresiva. El trastorno depresivo de la personalidad se caracteriza por un patrón de comportamiento depresivo crónico que manifiesta algunos de los siguientes síntomas: -Estado de ánimo caracterizado por abatimiento, tristeza, desánimo, desilusión e infelicidad -Autoconcepto basado en creencias de inadecuación, inutilidad y baja autoestima -Autocrítica y menosprecio -Preocupaciones -Lleva la contraria, critica y juzga a los demás -pesimismo -Culpabilidad y remordimiento. La personalidad depresiva se caracteriza por la incapacidad para relajarse y disfrutar, la seriedad y la falta de sentido del humor. Físicamente reflejan su estado de ánimo, presentando una postura hundida, ve vacía, expresión facial deprimida y retraso psicomotor. Les sus expectativas para el futuro son negativas y no creen que las cosas puedan mejorar.
EOF;
        }

        if ($data['Paranoide'] >= 3 && $data['Paranoide'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad paranoide. La característica esencial del trastorno paranoide de la personalidad es un patrón de desconfianza y suspicacia general hacia los otros, de forma que las intenciones de éstos son interpretadas como maliciosas. Este patrón empieza al principio de la edad adulta y aparece en diversos contextos.
EOF;
        } elseif ($data['Paranoide'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad paranoide. La característica esencial del trastorno paranoide de la personalidad es un patrón de desconfianza y suspicacia general hacia los otros, de forma que las intenciones de éstos son interpretadas como maliciosas. Este patrón empieza al principio de la edad adulta y aparece en diversos contextos.
EOF;
        }

        if ($data['Esquizotípico'] >= 4 && $data['Esquizotípico'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad esquizotípica. La característica esencial del trastorno esquizotípico de la personalidad es un patrón general de déficit sociales e interpersonales caracterizados por un malestar agudo y una capacidad reducida para las relaciones personales, así como por distorsiones cognoscitivas o perceptivas y excentricidades del comportamiento. Este patrón comienza al inicio de la edad adulta y se observa en diversos contextos.
EOF;
        } elseif ($data['Esquizotípico'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad esquizotípica. La característica esencial del trastorno esquizotípico de la personalidad es un patrón general de déficit sociales e interpersonales caracterizados por un malestar agudo y una capacidad reducida para las relaciones personales, así como por distorsiones cognoscitivas o perceptivas y excentricidades del comportamiento. Este patrón comienza al inicio de la edad adulta y se observa en diversos contextos.
EOF;
        }

        if ($data['Esquizoide'] >= 3 && $data['Esquizoide'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad esquizoide. La característica esencial del trastorno esquizoide de la personalidad es un patrón general de distanciamiento de las relaciones sociales y de restricción de la expresión emocional en el plano interpersonal. Este patrón comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con trastorno esquizoide de la personalidad no demuestran tener deseos de intimidad, parecen indiferentes a las oportunidades de establecer relaciones personales y no parece que les satisfaga demasiado formar parte de una familia o de un grupo social. Prefieren emplear el tiempo en sí mismos, más que estar con otras personas. Suelen estar socialmente aislados o ser «solitarios» y casi siempre escogen actividades solitarias o aficiones que no requieran interacciones con otras personas.  Prefieren las tareas mecánicas o abstractas como los juegos de ordenador o matemáticos. Pueden mostrar un interés muy escaso en tener experiencias sexuales con otra persona. Suele haber una reducción de la sensación de placer a partir de experiencias sensoriales, corporales o interpersonales, como pasear por una playa tomando el sol o hacer el amor.
EOF;
        } elseif ($data['Esquizoide'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad esquizoide. La característica esencial del trastorno esquizoide de la personalidad es un patrón general de distanciamiento de las relaciones sociales y de restricción de la expresión emocional en el plano interpersonal. Este patrón comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con trastorno esquizoide de la personalidad no demuestran tener deseos de intimidad, parecen indiferentes a las oportunidades de establecer relaciones personales y no parece que les satisfaga demasiado formar parte de una familia o de un grupo social. Prefieren emplear el tiempo en sí mismos, más que estar con otras personas. Suelen estar socialmente aislados o ser «solitarios» y casi siempre escogen actividades solitarias o aficiones que no requieran interacciones con otras personas.  Prefieren las tareas mecánicas o abstractas como los juegos de ordenador o matemáticos. Pueden mostrar un interés muy escaso en tener experiencias sexuales con otra persona. Suele haber una reducción de la sensación de placer a partir de experiencias sensoriales, corporales o interpersonales, como pasear por una playa tomando el sol o hacer el amor.
EOF;
        }

        if ($data['Histriónico'] >= 4 && $data['Histriónico'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad histriónica. La característica esencial del trastorno histriónico de la personalidad es la emotividad generalizada y excesiva y el comportamiento de búsqueda de atención. Este patrón empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno histriónico de la personalidad no están cómodos o se sienten despreciados cuando no son el centro de atención. En general son vivaces y dramáticos y tienden a llamar la atención, pudiendo, al principio, seducir a sus nuevos conocidos por su entusiasmo, por ser aparentemente muy abiertos o por ser seductores. Sin embargo, estas cualidades van atenuándose con el tiempo a medida que estos sujetos demandan continuamente ser el centro de atención. Hacen el papel de ser «el alma de la fiesta». Cuando no son el centro de atención pueden hacer algo dramático (por ejemplo: inventar historias, hacer un drama) para atraer la atención sobre sí mismos. Esta necesidad suele ser evidente en su comportamiento con el clínico (por ejemplo: adular, hacer regalos, hacer descripciones dramáticas de los síntomas físicos y psicológicos que son reemplazados por síntomas nuevos a cada visita).
EOF;
        } elseif ($data['Histriónico'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad histriónica. La característica esencial del trastorno histriónico de la personalidad es la emotividad generalizada y excesiva y el comportamiento de búsqueda de atención. Este patrón empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno histriónico de la personalidad no están cómodos o se sienten despreciados cuando no son el centro de atención. En general son vivaces y dramáticos y tienden a llamar la atención, pudiendo, al principio, seducir a sus nuevos conocidos por su entusiasmo, por ser aparentemente muy abiertos o por ser seductores. Sin embargo, estas cualidades van atenuándose con el tiempo a medida que estos sujetos demandan continuamente ser el centro de atención. Hacen el papel de ser «el alma de la fiesta». Cuando no son el centro de atención pueden hacer algo dramático (por ejemplo: inventar historias, hacer un drama) para atraer la atención sobre sí mismos. Esta necesidad suele ser evidente en su comportamiento con el clínico (por ejemplo: adular, hacer regalos, hacer descripciones dramáticas de los síntomas físicos y psicológicos que son reemplazados por síntomas nuevos a cada visita).
EOF;
        }

        if ($data['Narcisista'] >= 4 && $data['Narcisista'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad narcisista. La característica esencial del trastorno narcisista de la personalidad es un patrón general de grandiosidad, necesidad de admiración y falta de empatía que empieza al comienzo de la edad adulta y que se da en diversos contextos.
Los sujetos con este trastorno tienen un sentido grandioso de autoimportancia. Es habitual en ellos el sobrevalorar sus capacidades y exagerar sus conocimientos y cualidades, con lo que frecuentemente dan la impresión de ser jactanciosos y presuntuosos. Pueden asumir alegremente el que otros otorguen un valor exagerado a sus actos y sorprenderse cuando no reciben las alabanzas que esperan y que creen merecer. Es frecuente que de forma implícita en la exageración de sus logros se dé una infravaloración (devaluación) de la contribución de los demás. A menudo están preocupados por fantasías de éxito ilimitado, poder, brillantez, belleza o amor imaginarios. Pueden entregarse a rumiaciones sobre la admiración y los privilegios que «hace tiempo que les deben» y compararse favorablemente con gente famosa o privilegiada.
Los sujetos con trastorno narcisista de la personalidad creen que son superiores, especiales o únicos y esperan que los demás les reconozcan como tales. Piensan que sólo les pueden comprender o sólo pueden relacionarse con otras personas que son especiales o de alto status y atribuyen a aquellos con quienes tienen relación las cualidades de ser «únicos», «perfectos» o de tener «talento». Los sujetos con este trastorno creen que sus necesidades son especiales y fuera del alcance de la gente corriente. Su propia autoestima está aumentada (por reflejo) por el valor idealizado que asignan a aquellos con quienes se relacionan. Es probable que insistan en que sólo quieren a la persona «más importante» (médico, abogado, peluquero, profesor) o pertenecer a las «mejores» instituciones, pero pueden devaluar las credenciales de quienes les contrarían.
EOF;
        } elseif ($data['Narcisista'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad narcisista. La característica esencial del trastorno narcisista de la personalidad es un patrón general de grandiosidad, necesidad de admiración y falta de empatía que empieza al comienzo de la edad adulta y que se da en diversos contextos.
Los sujetos con este trastorno tienen un sentido grandioso de autoimportancia. Es habitual en ellos el sobrevalorar sus capacidades y exagerar sus conocimientos y cualidades, con lo que frecuentemente dan la impresión de ser jactanciosos y presuntuosos. Pueden asumir alegremente el que otros otorguen un valor exagerado a sus actos y sorprenderse cuando no reciben las alabanzas que esperan y que creen merecer. Es frecuente que de forma implícita en la exageración de sus logros se dé una infravaloración (devaluación) de la contribución de los demás. A menudo están preocupados por fantasías de éxito ilimitado, poder, brillantez, belleza o amor imaginarios. Pueden entregarse a rumiaciones sobre la admiración y los privilegios que «hace tiempo que les deben» y compararse favorablemente con gente famosa o privilegiada.
Los sujetos con trastorno narcisista de la personalidad creen que son superiores, especiales o únicos y esperan que los demás les reconozcan como tales. Piensan que sólo les pueden comprender o sólo pueden relacionarse con otras personas que son especiales o de alto status y atribuyen a aquellos con quienes tienen relación las cualidades de ser «únicos», «perfectos» o de tener «talento». Los sujetos con este trastorno creen que sus necesidades son especiales y fuera del alcance de la gente corriente. Su propia autoestima está aumentada (por reflejo) por el valor idealizado que asignan a aquellos con quienes se relacionan. Es probable que insistan en que sólo quieren a la persona «más importante» (médico, abogado, peluquero, profesor) o pertenecer a las «mejores» instituciones, pero pueden devaluar las credenciales de quienes les contrarían.
EOF;
        }

        if ($data['Límite'] >= 4 && $data['Límite'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad límite. La característica esencial del trastorno límite de la personalidad es un patrón general de inestabilidad en las relaciones interpersonales, la autoimagen y la afectividad, y una notable impulsividad que comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con un trastorno límite de la personalidad realizan frenéticos esfuerzos para evitar un abandono real o imaginado. La percepción de una inminente separación o rechazo, o la pérdida de la estructura externa, pueden ocasionar cambios profundos en la autoimagen, afectividad, cognición y comportamiento. Estos sujetos son muy sensibles a las circunstancias ambientales. Experimentan intensos temores a ser abandonados y una ira inapropiada incluso ante una separación que en realidad es por un tiempo limitado o cuando se producen cambios inevitables en los planes (por ejemplo: reacción de desesperación brusca cuando el clínico les anuncia el final de su tiempo de visita, angustia o enfurecimiento cuando alguien importante para ellos se retrasa aunque sea sólo unos minutos o cuando tiene que cancelar su cita). Pueden creer que este «abandono» implica el ser «malos».
Los individuos con un trastorno límite de la personalidad presentan un patrón de relaciones inestables e intensas. Pueden idealizar a quienes se ocupan de ellos o a sus amantes las primeras veces que se tratan, pedirles que estén mucho tiempo a su lado y compartir muy pronto los detalles más íntimos. Sin embargo, cambian rápidamente de idealizar a los demás a devaluarlos, pensando que no les prestan suficiente atención, no les dan demasiado o no «están» lo suficiente. Estos sujetos pueden empatizar y ofrecer algo a los demás, pero sólo con la expectativa de que la otra persona «esté allí» para corresponderles satisfaciendo sus propias necesidades o demandas. Son propensos asimismo a los cambios dramáticos en su opinión sobre los demás, que pueden ser vistos alternativamente como apoyos beneficiosos o cruelmente punitivos. Tales cambios suelen reflejar la desilusión con alguna de las personas que se ocupa de ellos y cuyas cualidades positivas han sido idealizadas o de quien se espera el rechazo o abandono.
EOF;
        } elseif ($data['Límite'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad límite. La característica esencial del trastorno límite de la personalidad es un patrón general de inestabilidad en las relaciones interpersonales, la autoimagen y la afectividad, y una notable impulsividad que comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con un trastorno límite de la personalidad realizan frenéticos esfuerzos para evitar un abandono real o imaginado. La percepción de una inminente separación o rechazo, o la pérdida de la estructura externa, pueden ocasionar cambios profundos en la autoimagen, afectividad, cognición y comportamiento. Estos sujetos son muy sensibles a las circunstancias ambientales. Experimentan intensos temores a ser abandonados y una ira inapropiada incluso ante una separación que en realidad es por un tiempo limitado o cuando se producen cambios inevitables en los planes (por ejemplo: reacción de desesperación brusca cuando el clínico les anuncia el final de su tiempo de visita, angustia o enfurecimiento cuando alguien importante para ellos se retrasa aunque sea sólo unos minutos o cuando tiene que cancelar su cita). Pueden creer que este «abandono» implica el ser «malos».
Los individuos con un trastorno límite de la personalidad presentan un patrón de relaciones inestables e intensas. Pueden idealizar a quienes se ocupan de ellos o a sus amantes las primeras veces que se tratan, pedirles que estén mucho tiempo a su lado y compartir muy pronto los detalles más íntimos. Sin embargo, cambian rápidamente de idealizar a los demás a devaluarlos, pensando que no les prestan suficiente atención, no les dan demasiado o no «están» lo suficiente. Estos sujetos pueden empatizar y ofrecer algo a los demás, pero sólo con la expectativa de que la otra persona «esté allí» para corresponderles satisfaciendo sus propias necesidades o demandas. Son propensos asimismo a los cambios dramáticos en su opinión sobre los demás, que pueden ser vistos alternativamente como apoyos beneficiosos o cruelmente punitivos. Tales cambios suelen reflejar la desilusión con alguna de las personas que se ocupa de ellos y cuyas cualidades positivas han sido idealizadas o de quien se espera el rechazo o abandono.
EOF;
        }

        if ($data['Antisocial'] >= 2 && $data['Antisocial'] <= 3) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad antisocial. La característica esencial del trastorno antisocial de la personalidad es un patrón general de desprecio y violación de los derechos de los demás, que comienza en la infancia o el principio de la adolescencia y continúa en la edad adulta.
Este patrón también ha sido denominado psicopatía, sociopatía o trastorno disocial de la personalidad. Puesto que el engaño y la manipulación son características centrales del trastorno antisocial de la personalidad, puede ser especialmente útil integrar la información obtenida en la evaluación clínica sistemática con la información recogida de fuentes colaterales.
EOF;
        } elseif ($data['Antisocial'] >= 4) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad antisocial. La característica esencial del trastorno antisocial de la personalidad es un patrón general de desprecio y violación de los derechos de los demás, que comienza en la infancia o el principio de la adolescencia y continúa en la edad adulta.
Este patrón también ha sido denominado psicopatía, sociopatía o trastorno disocial de la personalidad. Puesto que el engaño y la manipulación son características centrales del trastorno antisocial de la personalidad, puede ser especialmente útil integrar la información obtenida en la evaluación clínica sistemática con la información recogida de fuentes colaterales.
EOF;
        }

        $chart_labels = "'".implode("', '", array_keys($data))."'";
        $chart_data = [
            'Evitativo' => [
              'cut' => 4,
            ],
            'Dependencia' => [
              'cut' => 5,
            ],
            'Obsesivo' => [
              'cut' => 4,
            ],
            'Pasivo-Agresivo' => [
              'cut' => 4,
            ],
            'Depresivo' => [
              'cut' => 5,
            ],
            'Paranoide' => [
              'cut' => 4,
            ],
            'Esquizotípico' => [
              'cut' => 5,
            ],
            'Esquizoide' => [
              'cut' => 4,
            ],
            'Histriónico' => [
              'cut' => 5,
            ],
            'Narcisista' => [
              'cut' => 5,
            ],
            'Límite' => [
              'cut' => 5,
            ],
            'Antisocial' => [
              'cut' => 3,
            ],
        ];

        foreach ($data as $category => $val) {
            $chart_data[$category]['total'] = $val;
        }

        return [
            'exam' => $exam,
            'data' => $data,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_data,
            'total' => $total,
            'conclusions' => $conclusions
        ];
    }
}
