<?php

namespace App\Http\Controllers;

use App\Models\ToDo;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $todo = ToDo::all();

            if ($todo->isEmpty()) {
                return response()->json([
                    'message' => 'No data found'
                ], 404);
            }

            return response()->json([
                'message' => 'To Do List',
                'data' => $todo
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'task_name'=>'required|max:255',
                'description'=>'max:255',
            ];

            $validation= Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                ], 422);
            }

            $todo = ToDo::create([
                "task_name" => $request->task_name,
                "description" => $request->description,

            ]);

            return response()->json([
                'message' => 'To Do saved successfully',
                'data' => $todo
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to save data'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($search)
    {
        try {
            $todo = ToDo::where('task_name', 'ilike', "%{$search}%")
                    ->orWhere('id', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('status', 'ilike', "%{$search}%")
                    ->get();

            if ($todo->isEmpty()) {
                return response()->json([
                    'message' => 'No data found'
                ], 404);
            }

            return response()->json([
                'message' => 'To Do:',
                'data' => $todo
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ToDo $toDo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $todo = ToDo::findOrFail($id);

            $rules = array(
                'task_name'=>'required|max:255',
                'description'=>'max:255',
                'status'=>'string',

            );

            $validation= Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                ], 422);
            }


            $todo->update([
                "task_name" => $request->task_name,
                "description" => $request->description,
                "status" => $request->status,
            ]);

            return response()->json([
                'message' => "To Do updated successfully",
                'data' => $todo
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No data found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update data'], 500);
        }

    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $todo = ToDo::findOrFail($id);

            $todo->update([
                "status" => $request->status,
            ]);

            return response()->json([
                'message' => "To Do status updated successfully",
                'data' => $todo
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No data found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update status'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $todo = ToDo::findOrFail($id);

            $todo->delete();

            return response()->json([
                'message' => "To Do deleted successfully",
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'No data found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete data'], 500);
        }
    }
}
