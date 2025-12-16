<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectDocumentController extends Controller
{
    /**
     * عرض مستندات المشروع
     */
    public function index(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('view projects'), 403);

        $query = $project->documents()
            ->with('uploader')
            ->latest();

        // ✅ Active / Archived switch
        if ($request->boolean('archived')) {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        // 🔍 Search by file name
        if ($request->filled('q')) {
            $query->where('original_name', 'like', '%' . $request->q . '%');
        }

        // 📁 Filter by document type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 👤 Filter by uploader
        if ($request->filled('uploaded_by')) {
            $query->where('uploaded_by', $request->uploaded_by);
        }

        $documents = $query->paginate(10)->withQueryString();

        // Uploaders list for filter
        $uploaders = $project->documents()
            ->with('uploader')
            ->get()
            ->pluck('uploader')
            ->filter()
            ->unique('id')
            ->values();

        return view('projects.documents.index', compact(
            'project',
            'documents',
            'uploaders'
        ));
    }

    /**
     * رفع مستند جديد
     */
    public function store(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'file' => 'required|file|max:10240', // 10MB
        ]);

        $file = $validated['file'];

        $path = $file->store(
            'projects/' . $project->id,
            'public'
        );

        ProjectDocument::create([
            'project_id' => $project->id,
            'type' => $validated['type'],
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * حذف مستند
     */
    public function destroy(ProjectDocument $document)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        // حذف الملف من التخزين
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->back()
            ->with('success', 'Document deleted successfully.');
    }



    public function archive(Project $project, ProjectDocument $document)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        if ($document->project_id !== $project->id) {
            abort(404);
        }

        $document->update([
            'is_archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->id(),
        ]);

        return back()->with('success', 'Document archived successfully.');
    }

    public function restore(Project $project, ProjectDocument $document)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        if ($document->project_id !== $project->id) {
            abort(404);
        }

        $document->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null,
        ]);

        return back()->with('success', 'Document restored successfully.');
    }


    public function download(Project $project, ProjectDocument $document)
    {
        abort_unless(auth()->user()->can('view projects'), 403);

        // تأكيد أن المستند تابع للمشروع
        if ($document->project_id !== $project->id) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_name
        );
    }

}
