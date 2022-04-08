<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use App\Models\{
  Role,
  User,
  Task,
};

class TaskController extends Controller
{

  protected $list_relations = ['status', 'type', 'periodicity', 'approvedReply'];
  protected $item_relations = ['status', 'type', 'periodicity', 'replies', 'approvedReply'];


  /**
   * List
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request, $author_id, $course_id)
  {
    $user = $request->user();
    $task_list = Task::with($this->list_relations)->where('author_id', $user->id)->get();
    return response()->json(['user_list' => $user_list], 200);
  }

  /**
   * Get
   * @param int $task_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, $task_id)
  {
    $task = Task::with($this->list_relations)->find($task_id);
    return response()->json(['task' => $task], 200);
  }

  /**
   * Create
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function create(Request $request)
  {
    $user = $request->user();
    // Only for customers
    // TODO: move to middleware
    if ($user->role != Role::CUSTOMER) {
      return response()->json([], Response::HTTP_FORBIDDEN);
    }

    $input = $request->only(Task::getFillableByUser());
    $input['author_id'] = $user->id;
    $input['status_id'] = 1;

    $validator = Validator::make($input, Task::create_rules());
    if ($validator->fails())
      return response()->json(['errors' => $validator->errors()], 422);

    $task = Task::create($input);
    return response()->json(['task' => $task], Response::HTTP_CREATED);
  }

  /**
   * Publish
   * @param int $task_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function publish(Request $request, $task_id)
  {
    $user = $request->user();
    // TODO: move to middleware
    if ($user->role != Role::CUSTOMER) {
      return response()->json([], Response::HTTP_FORBIDDEN);
    }

    $task = Task::where('author_id', $user->id)->unpublished()->find($task_id);

    // Not found
    if (!$task) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }

    // Insufficient funds
    if ($user->balance < $task->price) {
      $message = 'Insufficient funds in the account';
      return response()->json(['message' => $message], Response::HTTP_PAYMENT_REQUIRED);
    }

    $data = [
      'published_at' => date("Y-m-d H:i:s"),
      'status_id' => 2,
    ];
    $task->update($data);
    return response()->json(['task' => $task], Response::HTTP_OK);
  }
}
