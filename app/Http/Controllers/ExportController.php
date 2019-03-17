<?php

namespace App\Http\Controllers;

use App\Helpers\CsvCreator;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Bueltge\Marksimple\Marksimple;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExportSelected;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function __construct()
    {
        // Only to test in the browser api auth
        Auth::loginUsingId(1);
    }

    public function welcome()
    {
        $ms = new Marksimple();

        return view('hello', [
            'content' => $ms->parseFile('../README.md'),
        ]);
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {
        $students = Student::with('course')->get();

        return view('view_students', compact(['students']));
    }

    /**
     * Exports selected students data to a CSV file
     * @param ExportSelected $request
     * @return mixed
     * @throws \Exception
     */
    public function export(ExportSelected $request)
    {
        $students = Student::with('courses')
            ->whereIn('id', $request->request->get('studentId'))
            ->get();

        $csv = new CsvCreator();
        $file = $csv->setPath(public_path('csv'))
            ->setData($students->toArray())
            ->useFields(['id', 'firstname', 'surname', 'email', 'nationality', 'course_id'])
            ->make();

        $filename = explode('/', $file);
        $filename = end($filename);

        return response()->download(($file), $filename, [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Content-Transfer-Encoding' => 'binary',
            'Connection' => 'Keep-Alive',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
        ]);
    }

    /*public function exportHistory()
    {
        dd(Storage::disk('public')->files());
    }*/

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV()
    {
        //
    }

    /**
     * Exports the total number of students that are taking each course to a CSV file
     */
    public function exportCourseAttendenceToCSV()
    {
        //
    }

    /** Optional **/

    /**
     * View all students found in the database
     */
    public function viewStudentsWithVue()
    {
        $students = Student::with('courses')->get();

        return view('view_students_vue', compact(['students']));
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCsvWithVue()
    {
        //
    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendenceToCsvWithVue()
    {
        //
    }
}
