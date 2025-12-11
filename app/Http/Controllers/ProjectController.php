<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'client' => 'nullable|string',
            'city' => 'nullable|string',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')
                         ->with('success', 'Project created successfully');
    }

    public function show($id)
    {
        $project = Project::with('devices', 'pmPlans', 'pmRecords')->findOrFail($id);

        return view('projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
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
