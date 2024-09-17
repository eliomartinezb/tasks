<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TasksController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Tasks::query();

        if (request()->has('project_id')) {
            $tasks = $tasks->where('project_id', request()->project_id);
        }

        $tasks = $tasks->with('project')
            ->orderBy('priority', 'asc')
            ->get();

        if ($tasks->isEmpty()) {
            return $this->sendError('There are no task available');
        }

        return $this->sendResponse($tasks, 'The tasks has been loaded successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return $this->sendError('The information sent has an error', ['error' => $validator->errors()], 400);
        }

        $task = $this->createOrUpdateTask($request);

        return $this->sendResponse($task, 'The task has been successfully created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Tasks::find($id);

        if (! $task) {
            return $this->sendError('We could not find the task that you are trying to retrieve', ['error' => 'We could not find the task you are trying to retrieve.']);
        }

        return $this->sendResponse($task, 'The task has been successfully found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tasks' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->sendError('The information sent has an error', ['error' => $validator->errors()], 400);
        }

        foreach ($request->tasks as $task_request) {
            $task = Tasks::find($task_request["id"]);
            $task->priority = $task_request["priority"];
            $task->save();
        }

        return $this->sendResponse($request->tasks, 'The task has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Tasks::find($id);

        if (! $task) {
            return $this->sendError('We could not find the task that you are trying to delete', ['error' => 'We could not find the task that you are trying to delete']);
        }

        $task->delete();

        return $this->sendResponse($task, 'The task has been successfully deleted');
    }

    protected function validateRequest(Request $request, ?Tasks $task = null): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'project_id' => 'required',
            'name' => 'required|string|max:255',
            'priority' => 'required|integer',
        ]);
    }

    protected function createOrUpdateTask(Request $request, $task = null)
    {
        return DB::transaction(function () use ($request, $task) {
            if ($task === null) {
                $task = new Tasks;
            }

            $task->project_id = $request->project_id;
            $task->name = $request->name;
            $task->priority = $request->priority;
            $task->save();

            return $task;
        });
    }
}
