<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Lista todos os cursos
    public function index()
    {
        $courses = Course::orderBy('name')->get();
        return view('admin.courses.index', compact('courses'));
    }

    // Salva o novo curso
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:courses']);
        Course::create(['name' => $request->name]);
        return redirect()->route('admin.courses.index')->with('status', 'Curso cadastrado com sucesso!');
    }

    // Atualiza um curso existente
    public function update(Request $request, Course $course)
    {
        $request->validate(['name' => 'required|string|max:255|unique:courses,name,' . $course->id]);
        $course->update(['name' => $request->name]);
        return redirect()->route('admin.courses.index')->with('status', 'Curso atualizado com sucesso!');
    }

    // Exclui o curso
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('status', 'Curso excluído com sucesso!');
    }
}