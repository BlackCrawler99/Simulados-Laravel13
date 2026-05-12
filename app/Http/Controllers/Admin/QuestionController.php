<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Exports\QuestionTemplateExport;
use App\Imports\QuestionImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('options')->latest()->paginate(10);
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'statement' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'options' => 'required|array|min:5|max:5',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0|max:4',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('questions', 'public');
        }

        $question = Question::create([
            'statement' => $request->statement,
            'image' => $imagePath,
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'text' => $optionText,
                'is_correct' => ($index == $request->correct_option),
            ]);
        }

        return redirect()->route('admin.questions.index')->with('status', 'Questão cadastrada com sucesso!');
    }

    public function edit(Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'statement' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'options' => 'required|array|min:5|max:5',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0|max:4',
        ]);

        $imagePath = $question->image;

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('questions', 'public');
        }

        $question->update([
            'statement' => $request->statement,
            'image' => $imagePath,
        ]);

        $options = $question->options;
        foreach ($request->options as $index => $optionText) {
            if (isset($options[$index])) {
                $options[$index]->update([
                    'text' => $optionText,
                    'is_correct' => ($index == $request->correct_option),
                ]);
            }
        }

        return redirect()->route('admin.questions.index')->with('status', 'Questão atualizada com sucesso!');
    }

    public function destroy(Question $question)
    {
        if ($question->image && Storage::disk('public')->exists($question->image)) {
            Storage::disk('public')->delete($question->image);
        }
        $question->options()->delete();
        $question->delete();
        return redirect()->route('admin.questions.index')->with('status', 'Questão excluída com sucesso!');
    }

    // MÉTODOS DE EXCEL
    public function downloadTemplate()
    {
        return Excel::download(new QuestionTemplateExport, 'modelo_importacao_questoes.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new QuestionImport, $request->file('excel_file'));
            return redirect()->route('admin.questions.index')->with('status', 'Questões importadas com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.index')->withErrors(['error' => 'Erro ao importar: ' . $e->getMessage()]);
        }
    }
}