<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{	
	/**
	 * The task repository instance.
	 * @var TaskRepository
	 */
	protected $tasks;


	/**
	 * Create a new controller instance.
	 *
	 * @return  void
	 */
    public function __construct(TaskRepository $tasks)
    {
    	//force authentication
    	$this->middleware('auth');

    	$this->tasks = $tasks;
    }

    /**
     * Display a list of all the user's tasks.
     * 
     * @param  Request $request 
     * @return Response
     */
    public function index(Request $request)
    {
    	return view('tasks.index', [
    		'tasks'=>$this->tasks->forUser($request->user()),
    	]);
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name'=>'required|max:255',
    	]);

    	//Create the Task...
    	$request->user()->tasks()->create([
    		'name'=>$request->name,
    	]);

    	return redirect('/tasks');
    }

    public funtion destroy(Request $request, Task $task)
    {
    	$this->authorize('destroy', $task);

    	$task->delete();

    	return redirect('/tasks');
    }
}
