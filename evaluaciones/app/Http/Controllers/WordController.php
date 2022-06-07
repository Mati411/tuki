<?php

namespace App\Http\Controllers;

use App\PatientsEvaluations;
use Illuminate\Http\Request;
use App\TemplateProcessors\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Shared\Converter;

class WordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form(Request $request, $uuid)
    {
        $exam = PatientsEvaluations::where('guid', $uuid)->first();

        if (! $exam)
            return view('exam.invalid');

        return view('admin.word.form', [
            'exam' => $exam,
        ]);
    }

    public function generate(Request $request, $uuid)
    {
        $exam = PatientsEvaluations::where('guid', $uuid)->first();

        if (! $exam)
            return view('exam.invalid');

        // echo '<pre>';
        // var_dump($request->input());
        // echo '</pre>';
        //
        // die();
        $reporter = new ReportsController();
        $data = $reporter->{'report'.$exam->evaluations_id}($exam);

        setlocale(LC_TIME, 'es_AR');

        $templateProcessor = new TemplateProcessor(storage_path().'/word/templates/template'.$exam->evaluations_id.'.docx');
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontSize(10);
        $phpWord->setDefaultFontName('Arial');

        $templateProcessor->setValue('lugar', $request->input('place'));
        $templateProcessor->setValue('fecha', date('d \d\e F \d\e\l Y', strtotime($request->input('date'))));
        $templateProcessor->setValue('cliente', $request->input('client'));
        $templateProcessor->setValue('nombre_paciente', $request->input('fullname'));
        $templateProcessor->setValue('edad_paciente', $request->input('age'));
        $templateProcessor->setValue('dni', $request->input('dni'));

        if ($request->input('remote') === '1') {
            $templateProcessor->setValue('remoto', 'La entrevista se realizó en forma remota vía conferencia virtual a través de la plataforma Meet, de acuerdo a lo normatizado por las Leyes Nacionales Nros. 27.541, Decretos Nros. DECNU 260-APN-PTE/20, y sus normas complementarias, DECNU-297-APN-PTE/20  y DECNU 325-APN-PTE/20; DECNU 355-APN-PTE/20; Leyes de la Ciudad Autónoma de Buenos Aires Nros. 265, 6.292,  Decreto Nº 463-GCABA-AJG/19; Resolución Nº 3397-GCABA-SSTIYC/1 por emergencia CoVid-19.');
        } else {
            $templateProcessor->setValue('remoto', '');
        }

        $templateProcessor->setHtmlBlockValue('motivo_adicional', $request->input('reason'));
        $templateProcessor->setHtmlBlockValue('desarrollo_entrevista', $request->input('interview'));
        $templateProcessor->setHtmlBlockValue('antecedentes', $request->input('history'));
        $templateProcessor->setHtmlBlockValue('niveles_funcionamiento', $request->input('levels'));
        $templateProcessor->setHtmlBlockValue('historia_personal', $request->input('social'));
        $templateProcessor->setHtmlBlockValue('antecedentes_clinicos', $request->input('clinical_history'));

        // grafico
        $chart_data = [
            [], // Valor empleado
            [], // Punto de corte
        ];
        $chart_labels = [];

        foreach ($data['chart_data'] as $category => $val) {
            $chart_labels[] = $category;
            $chart_data[0][] = $val['total'];
            $chart_data[1][] = $val['cut'];
        }

        $chart = new Chart('line', $chart_labels, $chart_data[0], [
            'showAxisLabels' => true,
            'showLegend' => true,
        ], 'Empleado');
        $chart->addSeries($chart_labels, $chart_data[1], 'Corte');
        $chart->getStyle()
            ->setWidth(Converter::cmToEmu(15))
            ->setHeight(Converter::cmToEmu(5))
            ->setShowGridY(true)
            ->setDataLabelOptions([
                'showVal' => false,
                'showCatName' => false,
            ]);

        $templateProcessor->setChart('grafico', $chart);

        // tabla1
        $table_data = [];
        foreach ($data['data'] as $category => $val) {
            $table_data[] = [
                'tabla1_categoria' => $category,
                'tabla1_puntaje' => $val,
            ];
        }
        $templateProcessor->cloneRowAndSetValues('tabla1_categoria', $table_data);

        // conclusiones
        $templateProcessor->cloneBlock('conclusiones', count($data['conclusions']), true, true);
        $tmp = 1;
        foreach ($data['conclusions'] as $conclusion) {
            $templateProcessor->setHtmlBlockValue('conclusion#'.$tmp++, '<p>'.$conclusion.'</p>');
        }

        $templateProcessor->setHtmlBlockValue('exploracion_psicopatologica', $request->input('psychopathological'));

        $templateProcessor->setValue('diagnostico_eje1', $request->input('diagnosis.1'));
        $templateProcessor->setValue('diagnostico_eje2', $request->input('diagnosis.2'));
        $templateProcessor->setValue('diagnostico_eje3', $request->input('diagnosis.3'));
        $templateProcessor->setValue('diagnostico_eje4', $request->input('diagnosis.4'));
        $templateProcessor->setValue('diagnostico_eje5', $request->input('diagnosis.5'));

        $templateProcessor->setHtmlBlockValue('consideraciones_laborales', $request->input('considerations'));

        // Firmas
        $signatures = $request->input('signatures');

        $tmp = 1;
        foreach ($signatures as $k => $v) {
            $templateProcessor->setImageValue('firma#'.$tmp++, [
                'path' => storage_path().'/word/signatures/'.$k.'.png',
                'width' => '200px',
                'height' => '200px',
            ]);
        }
        while ($tmp <= 5) {
            $templateProcessor->setValue('firma#'.$tmp++, '');
        }

        $outputPath = storage_path().'/word/output/'.md5(time()).'.docx';

        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath);
    }
}
