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
                'Depresi??n' => [
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
                'Ansiedad F??bica' => [
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
                'Depresi??n' => [
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
                'Ansiedad F??bica' => [
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
                'text' => 'Punt??a elevado para obsesiones. Precencia de pensamientos, acciones e impulsos que son vivenciados como imposibles de evitar o no deseados.',
                'threshold' => [
                    'MALE' => 2.69,
                    'FEMALE' => 2.94,
                ],
            ],
            [
                'category' => 'Sens. Interper',
                'text' => 'Presencia de s??ntomas de sensibilidad interpersonal, se focaliza en detectar la presencia de sentimientos de inferioridad e inadecuaci??n, en especial cuando la persona se compara con sus semejantes.',
                'threshold' => [
                    'MALE' => 1.97,
                    'FEMALE' => 2.33,
                ],
            ],
            [
                'category' => 'Depresi??n',
                'text' => 'Punt??a elevado para depresi??n: Estado de ??nimo disf??rico, falta de motivaci??n, poca energ??a vital, sentimientos de desesperanza, ideaciones suicidas.',
                'threshold' => [
                    'MALE' => 2.21,
                    'FEMALE' => 2.79,
                ],
            ],
            [
                'category' => 'Ansiedad',
                'text' => 'Presenta s??ntomas de ansiedad, tales como nerviosismo, tensi??n, ataques de p??nico, miedos.',
                'threshold' => [
                    'MALE' => 2.04,
                    'FEMALE' => 2.56,
                ],
            ],
            [
                'category' => 'Hostilidad',
                'text' => 'Existe un aumento en las respuesta del ??tem hostilidad. Esta dimensi??n hace referencia a pensamientos, sentimientos y acciones caracter??sticos de la presencia de afectos negativos de enojo.',
                'threshold' => [
                    'MALE' => 2.21,
                    'FEMALE' => 2.26,
                ],
            ],
            [
                'category' => 'Ansiedad F??bica',
                'text' => 'Ansiedad F??bica: Este malestar alude a una respuesta persistente de miedo (a personas espec??ficas, lugares, objetos, situaciones) que es en s?? misma irracional y desproporcionada en relaci??n con el est??mulo que la provoca.',
                'threshold' => [
                    'MALE' => 0.97,
                    'FEMALE' => 1.33,
                ],
            ],
            [
                'category' => 'Paranoia',
                'text' => 'Presenta s??ntomas de ideaci??n paranoide y comportamientos paranoides fundamentalmente en tanto des??rdenes del pensamiento: pensamiento proyectivo, suspicacia, temor a la p??rdida de autonom??a.',
                'threshold' => [
                    'MALE' => 2.41,
                    'FEMALE' => 2.58,
                ],
            ],
            [
                'category' => 'Psicoticismo',
                'text' => 'Indica presencia de s??ntomas referidos a estados de soledad, estilo de vida esquizoide, alucinaciones y control del pensamiento.',
                'threshold' => [
                    'MALE' => 1.39,
                    'FEMALE' => 1.53,
                ],
            ],
        ];

        $csl2 = [
            'TSP' => [
                'text' => 'El valor de la escala Total de S??ntomas Positivos indica tendencia a exagerar sus patolog??as.',
                'threshold' => [
                    'MALE' => 50.87,
                    'FEMALE' => 55.49,
                ],
            ],
            'IMPS' => [
                'text' => 'Las respuestas brindadas para este ??tem sugieren patrones de respuestas que deben analizarse en t??rminos de actitudes de fingimiento.',
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
            'Amn??sia' => [
                'total' => 0,
                'cut' => 3,
            ],
            'Bajo CI' => [
                'total' => 0,
                'cut' => 3,
            ],
            'Deterioro Neurol??gico' => [
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
Presenta rasgos de personalidad evitativa. La caracter??stica esencial del trastorno de la personalidad por evitaci??n es un patr??n general de inhibici??n social, unos sentimientos de inadecuaci??n y una hipersensibilidad a la evaluaci??n negativa que comienzan al principio de la edad adulta y que se dan en diversos contextos.
Los sujetos con trastorno de la personalidad por evitaci??n evitan trabajos o actividades escolares que impliquen un contacto interpersonal importante, porque tienen miedo de las cr??ticas, la desaprobaci??n o el rechazo. Pueden declinar las ofertas de promoci??n laboral debido a que las nuevas responsabilidades ocasionar??an cr??ticas de los compa??eros. Estos individuos evitan hacer nuevos amigos a no ser que est??n seguros de que van a ser apreciados y aceptados sin cr??ticas. Hasta que no superan pruebas muy exigentes que demuestren lo contrario, se considera que los dem??s son cr??ticos y les rechazan. Las personas con este trastorno no participan en actividades de grupo a no ser que reciban ofertas repetidas y generosas de apoyo y protecci??n. La intimidad personal suele ser dif??cil para ellos, aunque son capaces de establecer relaciones ??ntimas cuando hay seguridad de una aceptaci??n acr??tica. Pueden actuar con represi??n, tener dificultades para hablar de s?? mismos y tener sentimientos ??ntimos de temor a ser comprometidos, ridiculizados o avergonzados.
EOF;
        } elseif ($data['Evitativo'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad evitativa. La caracter??stica esencial del trastorno de la personalidad por evitaci??n es un patr??n general de inhibici??n social, unos sentimientos de inadecuaci??n y una hipersensibilidad a la evaluaci??n negativa que comienzan al principio de la edad adulta y que se dan en diversos contextos.
Los sujetos con trastorno de la personalidad por evitaci??n evitan trabajos o actividades escolares que impliquen un contacto interpersonal importante, porque tienen miedo de las cr??ticas, la desaprobaci??n o el rechazo. Pueden declinar las ofertas de promoci??n laboral debido a que las nuevas responsabilidades ocasionar??an cr??ticas de los compa??eros. Estos individuos evitan hacer nuevos amigos a no ser que est??n seguros de que van a ser apreciados y aceptados sin cr??ticas. Hasta que no superan pruebas muy exigentes que demuestren lo contrario, se considera que los dem??s son cr??ticos y les rechazan. Las personas con este trastorno no participan en actividades de grupo a no ser que reciban ofertas repetidas y generosas de apoyo y protecci??n. La intimidad personal suele ser dif??cil para ellos, aunque son capaces de establecer relaciones ??ntimas cuando hay seguridad de una aceptaci??n acr??tica. Pueden actuar con represi??n, tener dificultades para hablar de s?? mismos y tener sentimientos ??ntimos de temor a ser comprometidos, ridiculizados o avergonzados.
EOF;
        }

        if ($data['Dependencia'] >= 4 && $data['Dependencia'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad dependiente. La caracter??stica esencial del trastorno de la personalidad por dependencia es una necesidad general y excesiva de que se ocupen de uno, que ocasiona un comportamiento de sumisi??n y adhesi??n y temores de separaci??n. Este patr??n empieza al principio de la edad adulta y se da en diversos contextos. Los comportamientos dependientes y sumisos est??n destinados a provocar atenciones y surgen de una percepci??n de uno mismo como incapaz de funcionar adecuadamente sin la ayuda de los dem??s.
Los sujetos con trastorno de la personalidad por dependencia tienen grandes dificultades para tomar las decisiones cotidianas (por ejemplo, qu?? color de camisa escoger para ir a trabajar o si llevar paraguas o no), si no cuentan con un excesivo aconsejamiento y reafirmaci??n por parte de los dem??s. Estos individuos tienden a ser pasivos y a permitir que los dem??s (frecuentemente una sola persona) tomen las iniciativas y asuman la responsabilidad en las principales parcelas de su vida. Es t??pico que los adultos con este trastorno dependan de un progenitor o del c??nyuge para decidir d??nde deben vivir, qu?? tipo de trabajo han de tener y de qui??n tienen que ser amigos. Los adolescentes con este trastorno permitir??n que sus padres decidan qu?? ropa ponerse, con qui??n tienen que ir, c??mo tienen que emplear su tiempo libre y a qu?? escuela o colegio han de ir. Esta necesidad de que los dem??s asuman las responsabilidades va m??s all?? de lo que es apropiado para la edad o para la situaci??n en cuanto a pedir ayuda a los dem??s (por ejemplo, las necesidades espec??ficas de los ni??os, las personas mayores y los minusv??lidos). El trastorno de la personalidad por dependencia puede darse en un sujeto con una enfermedad m??dica o una incapacidad grave, pero en estos casos la dificultad para asumir responsabilidades debe ir m??s lejos de lo que normalmente se asocia a esa enfermedad o incapacidad.
EOF;
        } elseif ($data['Dependencia'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad dependiente. La caracter??stica esencial del trastorno de la personalidad por dependencia es una necesidad general y excesiva de que se ocupen de uno, que ocasiona un comportamiento de sumisi??n y adhesi??n y temores de separaci??n. Este patr??n empieza al principio de la edad adulta y se da en diversos contextos. Los comportamientos dependientes y sumisos est??n destinados a provocar atenciones y surgen de una percepci??n de uno mismo como incapaz de funcionar adecuadamente sin la ayuda de los dem??s.
Los sujetos con trastorno de la personalidad por dependencia tienen grandes dificultades para tomar las decisiones cotidianas (por ejemplo, qu?? color de camisa escoger para ir a trabajar o si llevar paraguas o no), si no cuentan con un excesivo aconsejamiento y reafirmaci??n por parte de los dem??s. Estos individuos tienden a ser pasivos y a permitir que los dem??s (frecuentemente una sola persona) tomen las iniciativas y asuman la responsabilidad en las principales parcelas de su vida. Es t??pico que los adultos con este trastorno dependan de un progenitor o del c??nyuge para decidir d??nde deben vivir, qu?? tipo de trabajo han de tener y de qui??n tienen que ser amigos. Los adolescentes con este trastorno permitir??n que sus padres decidan qu?? ropa ponerse, con qui??n tienen que ir, c??mo tienen que emplear su tiempo libre y a qu?? escuela o colegio han de ir. Esta necesidad de que los dem??s asuman las responsabilidades va m??s all?? de lo que es apropiado para la edad o para la situaci??n en cuanto a pedir ayuda a los dem??s (por ejemplo, las necesidades espec??ficas de los ni??os, las personas mayores y los minusv??lidos). El trastorno de la personalidad por dependencia puede darse en un sujeto con una enfermedad m??dica o una incapacidad grave, pero en estos casos la dificultad para asumir responsabilidades debe ir m??s lejos de lo que normalmente se asocia a esa enfermedad o incapacidad.
EOF;
        }

        if ($data['Obsesivo'] >= 3 && $data['Obsesivo'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad obsesiva. La caracter??stica esencial del trastorno obsesivo-compulsivo de la personalidad es una preocupaci??n por el orden, el perfeccionismo y el control mental e interpersonal, a expensas de la flexibilidad, la espontaneidad y la eficiencia. Este patr??n empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno obsesivo-compulsivo de la personalidad intentan mantener la sensaci??n de control mediante una atenci??n esmerada a las reglas, los detalles triviales, los protocolos, las listas, los horarios o las formalidades hasta el punto de perder de vista el objetivo principal de la actividad. Son excesivamente cuidadosos y propensos a las repeticiones, a prestar una atenci??n extraordinaria a los detalles y a comprobar repetidamente los posibles errores. No son conscientes del hecho de que las dem??s personas acostumbran a enfadarse por los retrasos y los inconvenientes que derivan de ese comportamiento. Por ejemplo, cuando estos individuos pierden una lista de las cosas que hay que hacer, son capaces de invertir una gran cantidad de tiempo busc??ndola, en lugar de emplear unos minutos en volver a confeccionarla de memoria y ponerse a hacer las tareas de que se trate. El tiempo se distribuye mal y las tareas m??s importantes se dejan para el ??ltimo momento. El perfeccionismo y los altos niveles de rendimiento que se autoimponen causan a estos sujetos una disfunci??n y un malestar significativos. Pueden estar tan interesados en llevar a cabo con absoluta perfecci??n cualquier detalle de un proyecto, que ??ste no se acabe nunca. Por ejemplo, se retrasa la finalizaci??n de un informe escrito debido al tiempo que se pierde en reescribirlo repetidas veces hasta que todo quede pr??cticamente ??perfecto??. Los objetivos se pierden y los aspectos que no constituyen el objetivo actual de la actividad pueden caer en el desorden.
EOF;
        } elseif ($data['Obsesivo'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad obsesiva. La caracter??stica esencial del trastorno obsesivo-compulsivo de la personalidad es una preocupaci??n por el orden, el perfeccionismo y el control mental e interpersonal, a expensas de la flexibilidad, la espontaneidad y la eficiencia. Este patr??n empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno obsesivo-compulsivo de la personalidad intentan mantener la sensaci??n de control mediante una atenci??n esmerada a las reglas, los detalles triviales, los protocolos, las listas, los horarios o las formalidades hasta el punto de perder de vista el objetivo principal de la actividad. Son excesivamente cuidadosos y propensos a las repeticiones, a prestar una atenci??n extraordinaria a los detalles y a comprobar repetidamente los posibles errores. No son conscientes del hecho de que las dem??s personas acostumbran a enfadarse por los retrasos y los inconvenientes que derivan de ese comportamiento. Por ejemplo, cuando estos individuos pierden una lista de las cosas que hay que hacer, son capaces de invertir una gran cantidad de tiempo busc??ndola, en lugar de emplear unos minutos en volver a confeccionarla de memoria y ponerse a hacer las tareas de que se trate. El tiempo se distribuye mal y las tareas m??s importantes se dejan para el ??ltimo momento. El perfeccionismo y los altos niveles de rendimiento que se autoimponen causan a estos sujetos una disfunci??n y un malestar significativos. Pueden estar tan interesados en llevar a cabo con absoluta perfecci??n cualquier detalle de un proyecto, que ??ste no se acabe nunca. Por ejemplo, se retrasa la finalizaci??n de un informe escrito debido al tiempo que se pierde en reescribirlo repetidas veces hasta que todo quede pr??cticamente ??perfecto??. Los objetivos se pierden y los aspectos que no constituyen el objetivo actual de la actividad pueden caer en el desorden.
EOF;
        }

        if ($data['Pasivo-Agresivo'] >= 3 && $data['Pasivo-Agresivo'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad pasivo-agresiva. El trastorno pasivo-agresivo de la personalidad tambi??n llamado negativista se caracteriza por un patr??n de actitudes de oposici??n y resistencia pasiva ante las demandas de una adecuada actuaci??n en situaciones sociales y laborales. la personalidad pasivo-agresiva expresa continuas quejas sobre su desgracia y de sentirse incomprendido y despreciado por dem??s. Menosprecia y critica de forma poco razonable a las figuras de autoridad con quien establece relaciones de hostilidad encubierta pero de dependencia. Muestra envidia y resentimiento hacia el ??xito ajeno y recurre a continuos intentos para frustrar los planes de los otros. Es frecuente la b??squeda continua de defectos en aquellas personas de las que dependen. A menudo la persona oscila entre las amenazas hostiles y el arrepentimiento. Es com??n que presente r??pidas sucesiones de estados de ??nimo e inestabilidad emocional. Son irritables y con baja tolerancia a la frustraci??n.
EOF;
        } elseif ($data['Pasivo-Agresivo'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad pasivo-agresiva. El trastorno pasivo-agresivo de la personalidad tambi??n llamado negativista se caracteriza por un patr??n de actitudes de oposici??n y resistencia pasiva ante las demandas de una adecuada actuaci??n en situaciones sociales y laborales. la personalidad pasivo-agresiva expresa continuas quejas sobre su desgracia y de sentirse incomprendido y despreciado por dem??s. Menosprecia y critica de forma poco razonable a las figuras de autoridad con quien establece relaciones de hostilidad encubierta pero de dependencia. Muestra envidia y resentimiento hacia el ??xito ajeno y recurre a continuos intentos para frustrar los planes de los otros. Es frecuente la b??squeda continua de defectos en aquellas personas de las que dependen. A menudo la persona oscila entre las amenazas hostiles y el arrepentimiento. Es com??n que presente r??pidas sucesiones de estados de ??nimo e inestabilidad emocional. Son irritables y con baja tolerancia a la frustraci??n.
EOF;
        }

        if ($data['Depresivo'] >= 4 && $data['Depresivo'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad depresiva. El trastorno depresivo de la personalidad se caracteriza por un patr??n de comportamiento depresivo cr??nico que manifiesta algunos de los siguientes s??ntomas: -Estado de ??nimo caracterizado por abatimiento, tristeza, des??nimo, desilusi??n e infelicidad -Autoconcepto basado en creencias de inadecuaci??n, inutilidad y baja autoestima -Autocr??tica y menosprecio -Preocupaciones -Lleva la contraria, critica y juzga a los dem??s -pesimismo -Culpabilidad y remordimiento. La personalidad depresiva se caracteriza por la incapacidad para relajarse y disfrutar, la seriedad y la falta de sentido del humor. F??sicamente reflejan su estado de ??nimo, presentando una postura hundida, ve vac??a, expresi??n facial deprimida y retraso psicomotor. Les sus expectativas para el futuro son negativas y no creen que las cosas puedan mejorar.
EOF;
        } elseif ($data['Depresivo'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad depresiva. El trastorno depresivo de la personalidad se caracteriza por un patr??n de comportamiento depresivo cr??nico que manifiesta algunos de los siguientes s??ntomas: -Estado de ??nimo caracterizado por abatimiento, tristeza, des??nimo, desilusi??n e infelicidad -Autoconcepto basado en creencias de inadecuaci??n, inutilidad y baja autoestima -Autocr??tica y menosprecio -Preocupaciones -Lleva la contraria, critica y juzga a los dem??s -pesimismo -Culpabilidad y remordimiento. La personalidad depresiva se caracteriza por la incapacidad para relajarse y disfrutar, la seriedad y la falta de sentido del humor. F??sicamente reflejan su estado de ??nimo, presentando una postura hundida, ve vac??a, expresi??n facial deprimida y retraso psicomotor. Les sus expectativas para el futuro son negativas y no creen que las cosas puedan mejorar.
EOF;
        }

        if ($data['Paranoide'] >= 3 && $data['Paranoide'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad paranoide. La caracter??stica esencial del trastorno paranoide de la personalidad es un patr??n de desconfianza y suspicacia general hacia los otros, de forma que las intenciones de ??stos son interpretadas como maliciosas. Este patr??n empieza al principio de la edad adulta y aparece en diversos contextos.
EOF;
        } elseif ($data['Paranoide'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad paranoide. La caracter??stica esencial del trastorno paranoide de la personalidad es un patr??n de desconfianza y suspicacia general hacia los otros, de forma que las intenciones de ??stos son interpretadas como maliciosas. Este patr??n empieza al principio de la edad adulta y aparece en diversos contextos.
EOF;
        }

        if ($data['Esquizot??pico'] >= 4 && $data['Esquizot??pico'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad esquizot??pica. La caracter??stica esencial del trastorno esquizot??pico de la personalidad es un patr??n general de d??ficit sociales e interpersonales caracterizados por un malestar agudo y una capacidad reducida para las relaciones personales, as?? como por distorsiones cognoscitivas o perceptivas y excentricidades del comportamiento. Este patr??n comienza al inicio de la edad adulta y se observa en diversos contextos.
EOF;
        } elseif ($data['Esquizot??pico'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad esquizot??pica. La caracter??stica esencial del trastorno esquizot??pico de la personalidad es un patr??n general de d??ficit sociales e interpersonales caracterizados por un malestar agudo y una capacidad reducida para las relaciones personales, as?? como por distorsiones cognoscitivas o perceptivas y excentricidades del comportamiento. Este patr??n comienza al inicio de la edad adulta y se observa en diversos contextos.
EOF;
        }

        if ($data['Esquizoide'] >= 3 && $data['Esquizoide'] <= 4) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad esquizoide. La caracter??stica esencial del trastorno esquizoide de la personalidad es un patr??n general de distanciamiento de las relaciones sociales y de restricci??n de la expresi??n emocional en el plano interpersonal. Este patr??n comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con trastorno esquizoide de la personalidad no demuestran tener deseos de intimidad, parecen indiferentes a las oportunidades de establecer relaciones personales y no parece que les satisfaga demasiado formar parte de una familia o de un grupo social. Prefieren emplear el tiempo en s?? mismos, m??s que estar con otras personas. Suelen estar socialmente aislados o ser ??solitarios?? y casi siempre escogen actividades solitarias o aficiones que no requieran interacciones con otras personas.  Prefieren las tareas mec??nicas o abstractas como los juegos de ordenador o matem??ticos. Pueden mostrar un inter??s muy escaso en tener experiencias sexuales con otra persona. Suele haber una reducci??n de la sensaci??n de placer a partir de experiencias sensoriales, corporales o interpersonales, como pasear por una playa tomando el sol o hacer el amor.
EOF;
        } elseif ($data['Esquizoide'] >= 5) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad esquizoide. La caracter??stica esencial del trastorno esquizoide de la personalidad es un patr??n general de distanciamiento de las relaciones sociales y de restricci??n de la expresi??n emocional en el plano interpersonal. Este patr??n comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con trastorno esquizoide de la personalidad no demuestran tener deseos de intimidad, parecen indiferentes a las oportunidades de establecer relaciones personales y no parece que les satisfaga demasiado formar parte de una familia o de un grupo social. Prefieren emplear el tiempo en s?? mismos, m??s que estar con otras personas. Suelen estar socialmente aislados o ser ??solitarios?? y casi siempre escogen actividades solitarias o aficiones que no requieran interacciones con otras personas.  Prefieren las tareas mec??nicas o abstractas como los juegos de ordenador o matem??ticos. Pueden mostrar un inter??s muy escaso en tener experiencias sexuales con otra persona. Suele haber una reducci??n de la sensaci??n de placer a partir de experiencias sensoriales, corporales o interpersonales, como pasear por una playa tomando el sol o hacer el amor.
EOF;
        }

        if ($data['Histri??nico'] >= 4 && $data['Histri??nico'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad histri??nica. La caracter??stica esencial del trastorno histri??nico de la personalidad es la emotividad generalizada y excesiva y el comportamiento de b??squeda de atenci??n. Este patr??n empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno histri??nico de la personalidad no est??n c??modos o se sienten despreciados cuando no son el centro de atenci??n. En general son vivaces y dram??ticos y tienden a llamar la atenci??n, pudiendo, al principio, seducir a sus nuevos conocidos por su entusiasmo, por ser aparentemente muy abiertos o por ser seductores. Sin embargo, estas cualidades van atenu??ndose con el tiempo a medida que estos sujetos demandan continuamente ser el centro de atenci??n. Hacen el papel de ser ??el alma de la fiesta??. Cuando no son el centro de atenci??n pueden hacer algo dram??tico (por ejemplo: inventar historias, hacer un drama) para atraer la atenci??n sobre s?? mismos. Esta necesidad suele ser evidente en su comportamiento con el cl??nico (por ejemplo: adular, hacer regalos, hacer descripciones dram??ticas de los s??ntomas f??sicos y psicol??gicos que son reemplazados por s??ntomas nuevos a cada visita).
EOF;
        } elseif ($data['Histri??nico'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad histri??nica. La caracter??stica esencial del trastorno histri??nico de la personalidad es la emotividad generalizada y excesiva y el comportamiento de b??squeda de atenci??n. Este patr??n empieza al principio de la edad adulta y se da en diversos contextos.
Los sujetos con trastorno histri??nico de la personalidad no est??n c??modos o se sienten despreciados cuando no son el centro de atenci??n. En general son vivaces y dram??ticos y tienden a llamar la atenci??n, pudiendo, al principio, seducir a sus nuevos conocidos por su entusiasmo, por ser aparentemente muy abiertos o por ser seductores. Sin embargo, estas cualidades van atenu??ndose con el tiempo a medida que estos sujetos demandan continuamente ser el centro de atenci??n. Hacen el papel de ser ??el alma de la fiesta??. Cuando no son el centro de atenci??n pueden hacer algo dram??tico (por ejemplo: inventar historias, hacer un drama) para atraer la atenci??n sobre s?? mismos. Esta necesidad suele ser evidente en su comportamiento con el cl??nico (por ejemplo: adular, hacer regalos, hacer descripciones dram??ticas de los s??ntomas f??sicos y psicol??gicos que son reemplazados por s??ntomas nuevos a cada visita).
EOF;
        }

        if ($data['Narcisista'] >= 4 && $data['Narcisista'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad narcisista. La caracter??stica esencial del trastorno narcisista de la personalidad es un patr??n general de grandiosidad, necesidad de admiraci??n y falta de empat??a que empieza al comienzo de la edad adulta y que se da en diversos contextos.
Los sujetos con este trastorno tienen un sentido grandioso de autoimportancia. Es habitual en ellos el sobrevalorar sus capacidades y exagerar sus conocimientos y cualidades, con lo que frecuentemente dan la impresi??n de ser jactanciosos y presuntuosos. Pueden asumir alegremente el que otros otorguen un valor exagerado a sus actos y sorprenderse cuando no reciben las alabanzas que esperan y que creen merecer. Es frecuente que de forma impl??cita en la exageraci??n de sus logros se d?? una infravaloraci??n (devaluaci??n) de la contribuci??n de los dem??s. A menudo est??n preocupados por fantas??as de ??xito ilimitado, poder, brillantez, belleza o amor imaginarios. Pueden entregarse a rumiaciones sobre la admiraci??n y los privilegios que ??hace tiempo que les deben?? y compararse favorablemente con gente famosa o privilegiada.
Los sujetos con trastorno narcisista de la personalidad creen que son superiores, especiales o ??nicos y esperan que los dem??s les reconozcan como tales. Piensan que s??lo les pueden comprender o s??lo pueden relacionarse con otras personas que son especiales o de alto status y atribuyen a aquellos con quienes tienen relaci??n las cualidades de ser ????nicos??, ??perfectos?? o de tener ??talento??. Los sujetos con este trastorno creen que sus necesidades son especiales y fuera del alcance de la gente corriente. Su propia autoestima est?? aumentada (por reflejo) por el valor idealizado que asignan a aquellos con quienes se relacionan. Es probable que insistan en que s??lo quieren a la persona ??m??s importante?? (m??dico, abogado, peluquero, profesor) o pertenecer a las ??mejores?? instituciones, pero pueden devaluar las credenciales de quienes les contrar??an.
EOF;
        } elseif ($data['Narcisista'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad narcisista. La caracter??stica esencial del trastorno narcisista de la personalidad es un patr??n general de grandiosidad, necesidad de admiraci??n y falta de empat??a que empieza al comienzo de la edad adulta y que se da en diversos contextos.
Los sujetos con este trastorno tienen un sentido grandioso de autoimportancia. Es habitual en ellos el sobrevalorar sus capacidades y exagerar sus conocimientos y cualidades, con lo que frecuentemente dan la impresi??n de ser jactanciosos y presuntuosos. Pueden asumir alegremente el que otros otorguen un valor exagerado a sus actos y sorprenderse cuando no reciben las alabanzas que esperan y que creen merecer. Es frecuente que de forma impl??cita en la exageraci??n de sus logros se d?? una infravaloraci??n (devaluaci??n) de la contribuci??n de los dem??s. A menudo est??n preocupados por fantas??as de ??xito ilimitado, poder, brillantez, belleza o amor imaginarios. Pueden entregarse a rumiaciones sobre la admiraci??n y los privilegios que ??hace tiempo que les deben?? y compararse favorablemente con gente famosa o privilegiada.
Los sujetos con trastorno narcisista de la personalidad creen que son superiores, especiales o ??nicos y esperan que los dem??s les reconozcan como tales. Piensan que s??lo les pueden comprender o s??lo pueden relacionarse con otras personas que son especiales o de alto status y atribuyen a aquellos con quienes tienen relaci??n las cualidades de ser ????nicos??, ??perfectos?? o de tener ??talento??. Los sujetos con este trastorno creen que sus necesidades son especiales y fuera del alcance de la gente corriente. Su propia autoestima est?? aumentada (por reflejo) por el valor idealizado que asignan a aquellos con quienes se relacionan. Es probable que insistan en que s??lo quieren a la persona ??m??s importante?? (m??dico, abogado, peluquero, profesor) o pertenecer a las ??mejores?? instituciones, pero pueden devaluar las credenciales de quienes les contrar??an.
EOF;
        }

        if ($data['L??mite'] >= 4 && $data['L??mite'] <= 5) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad l??mite. La caracter??stica esencial del trastorno l??mite de la personalidad es un patr??n general de inestabilidad en las relaciones interpersonales, la autoimagen y la afectividad, y una notable impulsividad que comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con un trastorno l??mite de la personalidad realizan fren??ticos esfuerzos para evitar un abandono real o imaginado. La percepci??n de una inminente separaci??n o rechazo, o la p??rdida de la estructura externa, pueden ocasionar cambios profundos en la autoimagen, afectividad, cognici??n y comportamiento. Estos sujetos son muy sensibles a las circunstancias ambientales. Experimentan intensos temores a ser abandonados y una ira inapropiada incluso ante una separaci??n que en realidad es por un tiempo limitado o cuando se producen cambios inevitables en los planes (por ejemplo: reacci??n de desesperaci??n brusca cuando el cl??nico les anuncia el final de su tiempo de visita, angustia o enfurecimiento cuando alguien importante para ellos se retrasa aunque sea s??lo unos minutos o cuando tiene que cancelar su cita). Pueden creer que este ??abandono?? implica el ser ??malos??.
Los individuos con un trastorno l??mite de la personalidad presentan un patr??n de relaciones inestables e intensas. Pueden idealizar a quienes se ocupan de ellos o a sus amantes las primeras veces que se tratan, pedirles que est??n mucho tiempo a su lado y compartir muy pronto los detalles m??s ??ntimos. Sin embargo, cambian r??pidamente de idealizar a los dem??s a devaluarlos, pensando que no les prestan suficiente atenci??n, no les dan demasiado o no ??est??n?? lo suficiente. Estos sujetos pueden empatizar y ofrecer algo a los dem??s, pero s??lo con la expectativa de que la otra persona ??est?? all???? para corresponderles satisfaciendo sus propias necesidades o demandas. Son propensos asimismo a los cambios dram??ticos en su opini??n sobre los dem??s, que pueden ser vistos alternativamente como apoyos beneficiosos o cruelmente punitivos. Tales cambios suelen reflejar la desilusi??n con alguna de las personas que se ocupa de ellos y cuyas cualidades positivas han sido idealizadas o de quien se espera el rechazo o abandono.
EOF;
        } elseif ($data['L??mite'] >= 6) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad l??mite. La caracter??stica esencial del trastorno l??mite de la personalidad es un patr??n general de inestabilidad en las relaciones interpersonales, la autoimagen y la afectividad, y una notable impulsividad que comienza al principio de la edad adulta y se da en diversos contextos. Los sujetos con un trastorno l??mite de la personalidad realizan fren??ticos esfuerzos para evitar un abandono real o imaginado. La percepci??n de una inminente separaci??n o rechazo, o la p??rdida de la estructura externa, pueden ocasionar cambios profundos en la autoimagen, afectividad, cognici??n y comportamiento. Estos sujetos son muy sensibles a las circunstancias ambientales. Experimentan intensos temores a ser abandonados y una ira inapropiada incluso ante una separaci??n que en realidad es por un tiempo limitado o cuando se producen cambios inevitables en los planes (por ejemplo: reacci??n de desesperaci??n brusca cuando el cl??nico les anuncia el final de su tiempo de visita, angustia o enfurecimiento cuando alguien importante para ellos se retrasa aunque sea s??lo unos minutos o cuando tiene que cancelar su cita). Pueden creer que este ??abandono?? implica el ser ??malos??.
Los individuos con un trastorno l??mite de la personalidad presentan un patr??n de relaciones inestables e intensas. Pueden idealizar a quienes se ocupan de ellos o a sus amantes las primeras veces que se tratan, pedirles que est??n mucho tiempo a su lado y compartir muy pronto los detalles m??s ??ntimos. Sin embargo, cambian r??pidamente de idealizar a los dem??s a devaluarlos, pensando que no les prestan suficiente atenci??n, no les dan demasiado o no ??est??n?? lo suficiente. Estos sujetos pueden empatizar y ofrecer algo a los dem??s, pero s??lo con la expectativa de que la otra persona ??est?? all???? para corresponderles satisfaciendo sus propias necesidades o demandas. Son propensos asimismo a los cambios dram??ticos en su opini??n sobre los dem??s, que pueden ser vistos alternativamente como apoyos beneficiosos o cruelmente punitivos. Tales cambios suelen reflejar la desilusi??n con alguna de las personas que se ocupa de ellos y cuyas cualidades positivas han sido idealizadas o de quien se espera el rechazo o abandono.
EOF;
        }

        if ($data['Antisocial'] >= 2 && $data['Antisocial'] <= 3) {
            $conclusions[] = <<<EOF
Presenta rasgos de personalidad antisocial. La caracter??stica esencial del trastorno antisocial de la personalidad es un patr??n general de desprecio y violaci??n de los derechos de los dem??s, que comienza en la infancia o el principio de la adolescencia y contin??a en la edad adulta.
Este patr??n tambi??n ha sido denominado psicopat??a, sociopat??a o trastorno disocial de la personalidad. Puesto que el enga??o y la manipulaci??n son caracter??sticas centrales del trastorno antisocial de la personalidad, puede ser especialmente ??til integrar la informaci??n obtenida en la evaluaci??n cl??nica sistem??tica con la informaci??n recogida de fuentes colaterales.
EOF;
        } elseif ($data['Antisocial'] >= 4) {
            $conclusions[] = <<<EOF
Presenta criterios para trastorno de personalidad antisocial. La caracter??stica esencial del trastorno antisocial de la personalidad es un patr??n general de desprecio y violaci??n de los derechos de los dem??s, que comienza en la infancia o el principio de la adolescencia y contin??a en la edad adulta.
Este patr??n tambi??n ha sido denominado psicopat??a, sociopat??a o trastorno disocial de la personalidad. Puesto que el enga??o y la manipulaci??n son caracter??sticas centrales del trastorno antisocial de la personalidad, puede ser especialmente ??til integrar la informaci??n obtenida en la evaluaci??n cl??nica sistem??tica con la informaci??n recogida de fuentes colaterales.
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
            'Esquizot??pico' => [
              'cut' => 5,
            ],
            'Esquizoide' => [
              'cut' => 4,
            ],
            'Histri??nico' => [
              'cut' => 5,
            ],
            'Narcisista' => [
              'cut' => 5,
            ],
            'L??mite' => [
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
