<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Lister les tâches de l'utilisateur connecté
    public function index()
    {
        $tasks = Auth::user()->tasks()->orderBy('created_at', 'desc')->get();

        return response()->json($tasks, 200);
    }

    // Ajouter une nouvelle tâche
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($task, 201);
    }

    // Modifier une tâche existante
    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->is_completed,
            'completed_at' => $request->is_completed ? now() : null,
        ]);

        return response()->json($task, 200);
    }

    // Supprimer une tâche
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès'], 200);
    }

    // Marquer une tâche comme terminée
    public function markComplete(Task $task)
    {
        $this->authorizeTask($task);

        $task->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        return response()->json($task, 200);
    }

    // Vérifier que l'utilisateur connecté est bien le propriétaire de la tâche
    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Accès interdit');
        }
    }
}
