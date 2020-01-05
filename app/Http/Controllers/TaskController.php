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
        $tasks = Task::with('children')->whereNull('parent_id')->get();
        $users = file_get_contents(config('global.usersEndpoint'));

        return response()->json(['tasks' => $tasks,'users' => json_decode($users)], 200);
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

        return response()->json($task, 201);
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
            $task = Task::with('children')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'something went wrong','exception'=> $e], 500);
        }
        //dd($task);
        // task updating process
        $status = $this->saveTask($task, $request);

        // if task is not properly saved then return failure message with 500 code
        if (!$status) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }

        if (!$request->parent_id && $request->is_done && !empty($task->children)) {
            $task->children()->update(['is_done' => $request->is_done]);
        }
        unset($task->children);

        return response()->json($task, 201);
    }

    /**
     * Create/Update process of the task
     *
     * @param Task $task
     * @param Request $request
     * @return boolean
     */
    private function saveTask($task, $request)
    {
        $task->user_id = $request->user_id;
        $task->title = $request->title;
        $task->points = $request->points;
        $task->is_done = $request->is_done;
        $task->points = $request->points;
        if($request->parent_id) {
            $task->parent_id = $request->parent_id;
            $parent = Task::findOrFail($request->parent_id);
            if ($parent->user_id != $request->user_id) {
                return 0;
            }
            if (!$request->is_done) {
                $parent->is_done = $request->is_done;
                $parent->save();
            }
        } else {
            $task->points = 0;
        }

        return $task->save();
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
