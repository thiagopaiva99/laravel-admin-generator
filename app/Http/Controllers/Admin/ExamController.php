<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExamDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Repositories\ExamRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\User;

class ExamController extends InfyOmBaseController
{
    /** @var  ExamRepository */
    private $examRepository;

    public function __construct(ExamRepository $examRepo)
    {
        $this->examRepository = $examRepo;
    }

    /**
     * Display a listing of the HealthPlan.
     *
     * @param ExamDataTable $examDataTable
     * @return Response
     */
    public function index(ExamDataTable $examDataTable)
    {
        return $examDataTable->render('admin.exams.index');
    }

    /**
     * Show the form for creating a new Exam.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Store a newly created Exam in storage.
     *
     * @param CreateExamRequest $request
     *
     * @return Response
     */
    public function store(CreateExamRequest $request)
    {
        $input = $request->all();

        $exam = $this->examRepository->create($input);

        Flash::success('Exame salvo com sucesso!');

        /// envio de email para todos medicos quando cadastrar novo especialização
        $options = array();
        $options['type'] = 'new_exam';
        $options['exam'] = $input['name'];
        EmailController::sendMultipleEmails(User::where('user_type', '2')->pluck('email', 'id'), $options);

        return redirect(route('admin.exams.index'));
    }

    /**
     * Display the specified Exam.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $exam = $this->examRepository->findWithoutFail($id);

        if (empty($exam)) {
            Flash::error('Exame não encontrado');

            return redirect(route('admin.exams.index'));
        }

        return view('admin.exams.show')->with('exam', $exam);
    }

    /**
     * Show the form for editing the specified Exam.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $exam = $this->examRepository->findWithoutFail($id);

        if (empty($exam)) {
            Flash::error('Exame não encontrado');

            return redirect(route('admin.exams.index'));
        }

        return view('admin.exams.edit')->with('exam', $exam);
    }

    /**
     * Update the specified Exam in storage.
     *
     * @param  int              $id
     * @param UpdateExamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExamRequest $request)
    {
        $exam = $this->examRepository->findWithoutFail($id);

        if (empty($exam)) {
            Flash::error('Exame não encontrado');

            return redirect(route('admin.exams.index'));
        }

        $exam = $this->examRepository->update($request->all(), $id);

        Flash::success('Exame atualizado com sucesso!');

        return redirect(route('admin.exams.index'));
    }

    /**
     * Remove the specified Exam from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $exam = $this->examRepository->findWithoutFail($id);

        if (empty($exam)) {
            Flash::error('Exame não encontrado');

            return redirect(route('admin.exams.index'));
        }

        $this->examRepository->delete($id);

        Flash::success('Exame deletado com sucesso!');

        return redirect(route('admin.exams.index'));
    }
}
