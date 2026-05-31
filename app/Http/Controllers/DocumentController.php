<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Revision;
use App\Events\DocumentUpdatedEvent;
use App\Events\CursorMovedEvent;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class DocumentController extends Controller
{
    /**
     * Menampilkan daftar semua dokumen di halaman dashboard.
     */
    // SESUDAH ✓
public function index()
{
    $documents = Document::where('user_id', auth()->id())->latest()->get();
    return view('dashboard', compact('documents'));
}

    /**
     * Menyimpan dokumen baru ke database dari form dashboard.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $document = Document::create([
            'title' => $request->title,
            'content' => '', // Default konten kosong saat baru dibuat
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('document.show', $document->id)
                         ->with('success', 'Dokumen berhasil dibuat!');
    }

    /**
     * Menampilkan halaman editor real-time untuk dokumen tertentu.
     */
    public function show($id)
    {
        $document = Document::with('revisions.user')->findOrFail($id);
        return view('editor', compact('document'));
    }

    /**
     * Sinkronisasi real-time: simpan konten & broadcast ke user lain (tanpa revisi).
     */
    public function sync(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|string',
        ]);

        $document = Document::findOrFail($id);
        $document->content = $request->content ?? '';
        $document->save();

        $broadcasted = $this->broadcastDocumentUpdate($id, $document->content);

        return response()->json([
            'status' => 'synced',
            'broadcast' => $broadcasted,
        ]);
    }

    /**
     * Broadcast perubahan dokumen; tidak gagalkan request jika Reverb mati.
     */
    private function broadcastDocumentUpdate(int|string $documentId, string $content, bool $toOthers = true): bool
    {
        try {
            $event = new DocumentUpdatedEvent($documentId, $content, Auth::id());
            $pending = broadcast($event);

            if ($toOthers) {
                $pending->toOthers();
            }

            return true;
        } catch (BroadcastException|Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Menyimpan snapshot revisi (dipanggil lebih jarang dari sync).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|string',
        ]);

        $document = Document::findOrFail($id);
        $document->content = $request->content ?? '';
        $document->save();

        Revision::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'content' => $document->content,
        ]);

        return response()->json(['status' => 'saved']);
    }

    /**
     * Memancarkan koordinat kursor user secara real-time.
     */
    public function moveCursor(Request $request, $id)
{
    $user = auth()->user();

    try {
        broadcast(new CursorMovedEvent(
            documentId: (int) $id,
            userId:     $user->id,
            userName:   $user->name,
            position:   (int) $request->input('position', 0),
        ))->toOthers();
    } catch (\Throwable $e) {
        report($e);
    }

    return response()->json(['ok' => true]);
}
    /**
     * Mengembalikan konten dokumen ke versi revisi tertentu.
     */
    public function restore($id)
    {
        $revision = Revision::findOrFail($id);
        
        $document = Document::findOrFail($revision->document_id);
        $document->content = $revision->content;
        $document->save();

        $this->broadcastDocumentUpdate($document->id, $document->content, false);

        return redirect()->route('document.show', $document->id)
                         ->with('success', 'Dokumen berhasil dikembalikan ke versi sebelumnya!');
    }
}