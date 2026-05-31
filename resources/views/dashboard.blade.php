@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-5xl mx-auto space-y-8">
        <div class="overflow-hidden rounded-[32px] bg-gradient-to-r from-sky-600 via-indigo-600 to-purple-600 shadow-xl">
            <div class="px-6 py-8 sm:px-10 sm:py-10">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-sky-200">My documents</p>
                        <h1 class="mt-4 text-4xl font-bold text-white">Selamat datang, {{ Auth::user()->name }}!</h1>
                        <p class="mt-3 max-w-2xl text-slate-100/90">Buat dokumen baru, kelola revisi, dan tulis dengan pengalaman antarmuka yang bersih dan fokus.</p>
                    </div>
                    <form action="{{ route('document.store') }}" method="POST" class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                        @csrf
                        <input type="text" name="title" placeholder="Judul dokumen baru..." required
                               class="w-full rounded-2xl border border-white/30 bg-white/10 px-4 py-3 text-sm text-white placeholder-slate-200 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 sm:w-80">
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-slate-900/10 transition hover:bg-slate-100">
                            + Buat Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm">
            <div class="px-6 py-8 sm:px-10">
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900">My Documents</h2>
                        <p class="mt-2 text-sm text-slate-500">Semua dokumenmu tersedia di sini. Klik untuk mulai mengedit.</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-200">
                    @forelse($documents as $doc)
                        <div class="flex flex-col gap-4 py-5 md:flex-row md:items-center md:justify-between hover:bg-slate-50 transition-all">
                            <div>
                                <a href="{{ route('document.show', $doc->id) }}" class="text-lg font-semibold text-sky-600 hover:underline block">
                                    📄 {{ $doc->title }}
                                </a>
                                <span class="text-xs text-slate-400">
                                    Dibuat: {{ $doc->created_at ? $doc->created_at->diffForHumans() : 'Waktu tidak tersedia' }}
                                </span>
                            </div>
                            <a href="{{ route('document.show', $doc->id) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                                Buka Editor →
                            </a>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-sm">
                            Belum ada dokumen. Tulis judul di atas untuk membuat dokumen pertamamu!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection