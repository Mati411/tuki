<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\PatientsEvaluations;
use App\Evaluations;
use DataTables;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('admin_exam_list');
    }

    public function examList(Request $request)
    {
        if ($request->ajax()) {
            $data = PatientsEvaluations::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('answered', '{{ $answered == 1 ? "Si" : "No" }}')
                    ->addColumn('action', function($row){
                            $btn = '';
                            if ($row['answered'] == 1) {
                                $btn .= '<a href="'.route('admin_exam_report', ['uuid' => $row['guid']]).'" class="edit btn btn-default btn-sm" title="Ver reporte"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></a>';
                            }

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.exam.list');
    }

    public function createLink()
    {
        return view('admin.createLink', ['evaluations' => Evaluations::all()]);
    }

    public function createLink2(Request $request)
    {
        $evaluation = Evaluations::find($request->input('evaluations_id'));
        $reference = $request->input('reference');
        $gender = $request->input('gender');

        if (! $evaluation || empty($reference))
            abort(400);

        if (! in_array($gender, ['MALE','FEMALE']))
            abort(400);

        $uuid = Str::uuid()->toString();
        $exam = new PatientsEvaluations();
        $exam->evaluations_id = $evaluation->evaluations_id;
        $exam->guid = $uuid;
        $exam->reference = $reference;
        $exam->gender = $gender;
        $exam->save();

        return view('admin.createLink2  ', ['exam' => $exam]);
    }
}
