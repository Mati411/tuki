<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PatientsEvaluations;
use App\PatientsEvaluationsAnswers;

class EvaluationsController extends Controller
{
    public function view($uuid)
    {
        $exam = PatientsEvaluations::where('guid', $uuid)->first();

        if (! $exam || $exam->answered)
            return view('exam.invalid');

        return view('exam.form', ['exam' => $exam ]);
    }

    public function save(Request $request, $uuid)
    {
        $exam = PatientsEvaluations::where('guid', $uuid)->first();

        if (! $exam || $exam->answered)
            return view('exam.invalid');

        $input = $request->all();
        $answers = [];

        if (empty($input['answers'])) {
            abort(400);
        }

        foreach ($exam->evaluation->questions as $question) {
            $value = $input['answers'][$question->evaluations_questions_id];
            if (! isset($value)) {
                abort(400);
            }
            $answers[] = [
                'patients_evaluations_id' => $exam->patients_evaluations_id,
                'evaluations_questions_id' => $question->evaluations_questions_id,
                'value' => $value,
            ];
        }

        DB::beginTransaction();

        try {
            DB::table('patients_evaluations_answers')->insert($answers);

            $exam->answered = true;
            $exam->answered_on = date('Y-m-d H:i:s');
            $exam->save();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }

        return view('exam.received', ['exam' => $exam ]);
    }
}
