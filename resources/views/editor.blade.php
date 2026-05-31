@extends('layouts.app')

@section('content')
<div id="editor-app" class="min-h-screen bg-slate-50 py-10" 
    data-document-id="{{ $document->id }}" 
    data-user-id="{{ auth()->id() }}"
    data-user-name="{{ auth()->user()->name }}">
        <div class="rounded-[32px] bg-white border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <a href="{{ route('dashboard') }}" class="text-sm text-slate-500 transition hover:text-slate-900">← Kembali ke daftar</a>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">{{ $document->title }}</h1>
                    <p class="mt-2 text-sm text-slate-500">Tulis bebas, setiap perubahan akan disimpan secara real-time.</p>
                </div>
                <span id="editor-status" class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                    Menghubungkan...
                </span>
            </div>
        </div>

        <div class="rounded-[32px] bg-white border border-slate-200 shadow-sm p-6">
            <textarea
                id="editor"
                class="w-full min-h-[480px] resize-y rounded-3xl border border-slate-200 bg-slate-50 p-5 text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/20"
                placeholder="Mulai menulis..."
            >{{ old('content', $document->content) }}</textarea>
        </div>

        @if($document->revisions->isNotEmpty())
        <div class="rounded-[32px] bg-white border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-semibold text-slate-900 mb-4">Riwayat Revisi</h2>
            <ul id="revision-list" class="divide-y divide-slate-100">
                @foreach($document->revisions as $revision)
                <li class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm text-slate-600">{{ $revision->user->name ?? 'User' }} — {{ $revision->created_at->diffForHumans() }}</span>
                    <form action="{{ route('revision.restore', $revision->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">Pulihkan</button>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/editor.js'])
@endpush
