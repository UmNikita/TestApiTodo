<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Task extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = DB::table('task')->join('status', 'status.id', '=', 'task.status_id')
        ->select('task.id', 'task.name', 'task.description', 'status.name as status')
        ->get();
        return response()->json($tasks);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);

        $data = $request->only(['name', 'description',  'status']);
        
        $status_id = DB::table('status')->select('id')->where('name', '=', $data['status'])->value('id');
        if(!$status_id) {
            return response()->json(['Error' => 'Invalid status'], 400);
        }

        DB::table('task')->insert([
            'name' => $data['name'],
            'description' => $data['description'],
            'status_id' => $status_id
        ]);
        return response()->json(['message' => 'Task created'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!ctype_digit($id)) {
            return response()->json(['error' => 'Invalid ID'], 400);
        }
        
        $tasks = DB::table('task')->join('status', 'status.id', '=', 'task.status_id')
        ->select('task.id', 'task.name', 'task.description', 'status.name as status')
        ->where('task.id', '=', $id)
        ->first();
        
        return response()->json($tasks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!ctype_digit($id)) {
            return response()->json(['error' => 'Invalid ID'], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);

        $data = $request->only(['name', 'description',  'status']);

        $status_id = DB::table('status')->select('id')->where('name', '=', $data['status'])->value('id');
        if(!$status_id) {
            return response()->json(['Error' => 'Invalid status'], 400);
        }

        DB::table('task')->updateOrInsert(['id' => $id], [
            'name' => $data['name'],
            'description' => $data['description'],
            'status_id' => $status_id
        ]);
        return response()->json(['message' => 'Task updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!ctype_digit($id)) {
            return response()->json(['error' => 'Invalid ID'], 400);
        }
        
        $deleted = DB::table('task')->where('id', '=', $id)->delete();
        
        if($deleted) {
            return response()->noContent();
        }
        else {
            return response()->json(['Error' => 'Task not found!'], 404);
        }        
        
    }
}
