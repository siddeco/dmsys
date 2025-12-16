<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::withCount([
            'devices' => function ($q) {
                $q->where('is_archived', false);
            }
        ]);

        // 🔍 Search
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('client', 'like', "%{$request->q}%");
            });
        }

        // 🏢 Client filter
        if ($request->filled('client')) {
            $query->where('client', $request->client);
        }

        // 🌍 City filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $projects = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }


    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        // =========================
        // Validate Project
        // =========================
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',

            // Documents
            'documents.type.*' => 'nullable|string',
            'documents.file.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        // =========================
        // Create Project
        // =========================
        $project = Project::create([
            'name' => $validated['name'],
            'client' => $validated['client'] ?? null,
            'city' => $validated['city'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // =========================
        // Handle Documents
        // =========================
        if ($request->has('documents.file')) {

            foreach ($request->file('documents.file') as $index => $file) {

                if (!$file) {
                    continue;
                }

                $type = $request->documents['type'][$index] ?? 'other';

                // Store file
                $path = $file->store(
                    'projects/' . $project->id,
                    'public'
                );

                // Save DB record
                ProjectDocument::create([
                    'project_id' => $project->id,
                    'type' => $type,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }

        return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'Project created successfully with documents.');
    }


    public function show(Project $project)
    {
        $devices = $project->devices()
            ->where('is_archived', false)
            ->paginate(10)
            ->appends([
                'tab' => 'devices'
            ]);




        $devicesCount = $project->devices()->count();

        $devicesByStatus = $project->devices()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('projects.show', compact(
            'project',
            'devices',
            'devicesCount',
            'devicesByStatus'
        ));
    }

    public function edit(Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        return view('projects.edit', compact('project'));
    }
    public function update(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'Project updated successfully.');
    }
    public function destroy($id)
    {
        Project::findOrFail($id)->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}
