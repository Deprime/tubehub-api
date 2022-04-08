<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\{
  User,
  Student,
  StudyGroup,
};

class StudentController extends Controller
{
  protected $list_relations = ['study_group'];
  protected $item_relations = ['study_group'];

  /**
   * List
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request)
  {
    $per_page = 15;
    $student_list = Student::with($this->list_relations)
                           ->paginate($per_page);
    return response()->json(['student_list' => $student_list], Response::HTTP_OK);
  }

  /**
   * Get
   * @param int $student_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, $student_id)
  {
    $student = Student::with($this->item_relations)->find($student_id);
    // Not found
    if (!$student) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }
    return response()->json(['student' => $student], Response::HTTP_OK);
  }

  /**
   * Create
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function create(Request $request)
  {
    $user  = auth()->user();
    // $input = $request->only(Student::getFillable());
    $input = $request->all();
    $input['author_id'] = $user->id;

    $validator = Validator::make($input, Student::create_rules());
    if ($validator->fails())
      return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);

    $student = Student::create($input);
    return response()->json(['student' => $student], Response::HTTP_CREATED);
  }

  /**
   * Update
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, $student_id)
  {
    $student = Student::with($this->item_relations)->find($student_id);
    // Not found
    if (!$student) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }

    $user  = $request->user();
    $input = $request->all($student->getFillable());

    $validator = Validator::make($input, Student::update_rules());
    if ($validator->fails())
      return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);

    $input['author_id'] = $student->author_id;
    $student->update($input);
    return response()->json(['student' => $student], Response::HTTP_OK);
  }

  /**
   * Delete
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function delete(Request $request, $student_id)
  {
    $student = Student::with($this->item_relations)->find($student_id);
    // Not found
    if (!$student) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }

    $student->delete();
    return response()->json([], Response::HTTP_OK);
  }
}
