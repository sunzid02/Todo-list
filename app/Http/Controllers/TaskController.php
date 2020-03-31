<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Response;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return TaskResource::collection($tasks);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Task::create([
            'name' => $request->name
        ]);

        return \json_encode(true);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        Task::where('id', $request->id)->update([
            'name' => $request->name,
            'updated_at' => Carbon::now(),
            'editing' => false,
        ]);

        return \json_encode(true);

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        // dd($article);   

        if ( $task->delete() ) 
        {
            return \json_encode(true);
            # code...
        }
    }



    public function completeTask(Request $request)
    {
        // dd($request->all());
        Task::where('id', $request->id)->update([
            'updated_at' => Carbon::now(),
            'completed' => 1,
        ]);

        return \json_encode(true);

    }

    public function completeTaskDestroy(Request $request)
    {
        // dd($request->all());
        Task::where('completed', 1)->delete();

        return \json_encode(true);

    }


    





}
