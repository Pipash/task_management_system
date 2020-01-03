<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Integer;

class TaskController extends Controller
{
    /**
     * Get all tasks with children
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Task::with('children')->get();

        return response()->json(['tasks'=> $tasks]);
    }

    /**
     * Create task
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTask(Request $request)
    {
        // validate the input
        $validator = $this->validator($request->all());
        // If validation fails then return with status failed and validation error message
        if ($validator->fails()) {
            return response()->json(['validationErrors' => $validator->errors()], 400);
        }

        // Create new task
        $task = new Task();
        // task creating process
        $status = $this->saveTask($task, $request);

        // if task is not properly saved then return failure message with 500 code
        if (!$status) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }

        return response()->json(['message' => 'Successfully saved!'], 201);
    }

    /**
     * Update Task
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTask(Request $request, $id)
    {
        // validate the input
        $validator = $this->validator($request->all());
        // If validation fails then return with status failed and validation error message
        if ($validator->fails()) {
            return response()->json(['validationErrors' => $validator->errors()], 400);
        }

        // Find task
        try {
            $task = Task::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'something went wrong','exception'=> $e], 500);
        }
        // task updating process
        $status = $this->saveTask($task, $request);

        // if task is not properly saved then return failure message with 500 code
        if (!$status) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }

        return response()->json(['message' => 'Successfully saved!'], 201);
    }

    /**
     * Save/Update process of the task
     *
     * @param Task $task
     * @param Request $request
     * @return boolean
     */
    private function saveTask($task, $request)
    {
        if($request->parent_id) {
            $task->parent_id = $request->parent_id;
        }
        $task->user_id = $request->user_id;
        $task->title = $request->title;
        $task->points = $request->points;
        $task->is_done = $request->is_done;
        $status = $task->save();

        // update all children product with the parent product id
        if (!empty($request->parent_id)) {
            try {
                $parentTask = Task::findOrFail($request->parent_id);
            } catch(ModelNotFoundException $e) {
                return response()->json(['message'=>'something went wrong','exception'=> $e], 500);
            }

            $parentTask->points += $request->points;
            $status = $parentTask->save();
        }

        return $status;
    }

    /**
     * Set validation rules
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator($data)
    {
        $validator = Validator::make($data,
            [
                'parent_id' => 'nullable|numeric|gt:0|exists:tasks,id',
                'user_id' => 'required|numeric|gt:0',
                'title' => 'required|string',
                'points' => 'required|numeric|gte:1|lte:10',
                'is_done' => 'required|numeric|gte:0|lte:1',
            ]
        );

        return $validator;
    }
}
