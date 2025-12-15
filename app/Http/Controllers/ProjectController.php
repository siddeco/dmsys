<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

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

    $validated = $request->validate([
        'name'        => 'required|string',
        'client'      => 'nullable|string',
        'city'        => 'nullable|string',
        'start_date'  => 'nullable|date',
        'end_date'    => 'nullable|date',
        'description' => 'nullable|string',

        'documents.file.*' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:5120',
        'documents.type.*' => 'nullable|string',
    ]);

    // 1️⃣ إنشاء المشروع
    $project = Project::create([
        'name'        => $validated['name'],
        'client'      => $validated['client'] ?? null,
        'city'        => $validated['city'] ?? null,
        'start_date'  => $validated['start_date'] ?? null,
        'end_date'    => $validated['end_date'] ?? null,
        'description' => $validated['description'] ?? null,
    ]);

    // 2️⃣ حفظ المستندات (إن وُجدت)
    if ($request->has('documents.file')) {

        foreach ($request->documents['file'] as $index => $file) {

            if (!$file) continue;

            $path = $file->store(
                "projects/{$project->id}",
                'public'
            );

            ProjectDocument::create([
                'project_id'    => $project->id,
                'type'          => $request->documents['type'][$index] ?? 'other',
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }
    }

    return redirect()
        ->route('projects.index')
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


    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    public function destroy($id)
    {
        Project::findOrFail($id)->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}
