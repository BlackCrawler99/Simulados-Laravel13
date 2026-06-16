<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mapeamento Vocacional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-gray-900">Mapeamento Vocacional</h1>
                <p class="mt-2 text-gray-600">Responda com sinceridade. Não existem respostas certas ou erradas!</p>
            </div>

            <form action="{{ route('vocational.submit') }}" method="POST" class="space-y-8">
                @csrf

                @foreach($questions as $index => $question)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">
                            <span class="text-emerald-600 mr-2">{{ $index + 1 }}.</span> {{ $question->text }}
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($question->options as $option)
                                <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition-colors group relative">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                    <span class="ml-4 text-gray-700 font-medium group-hover:text-emerald-800">{{ $option->text }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end pt-6 pb-12">
                    <button type="submit" class="bg-emerald-600 text-white px-8 py-4 rounded-xl font-black text-lg hover:bg-emerald-700 transition-colors shadow-lg flex items-center gap-2">
                        Descobrir meu Perfil
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>