<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\User;
use App\Model\Book;
use App\Model\Cart;
use App\Model\course;
use App\Model\session;
use App\Model\app_update;
use App\Model\enrolled_course;
use App\Model\Order;
use App\Model\Publisher;
use App\Model\Comment;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class UserController extends Controller
{
    public function login(Request $request)
{
    $mobile = $request->input('mobile');
    $password = $request->input('password');

    if (empty($mobile) || empty($password)) {
        return response()->json([
            'success' => false,
            'message' => 'Mobile or password is empty.',
        ], 200);
    }

    // Check if a user with the given mobile number exists in the database
    $user = User::where('mobile', $mobile)->first();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid mobile.',
        ], 200);
    }

    // Verify the password
    if (!Hash::check($password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid password.',
        ], 200);
    }

    return response()->json([
        'success' => true,
        'message' => 'Logged in successfully.',
        'data' => $user,
    ], 201);
}

//signin


public function Register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'name' => 'required',
        'mobile' => 'required',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
        ], 200);
    }

    $email = $request->input('email');
    $name = $request->input('name');
    $mobile = $request->input('mobile');
    $password = $request->input('password');
    $confirmPassword = $request->input('confirm_password');
    $referCode = Str::random(8); // Generate a random refer code

    $existingUser = User::where('mobile', $mobile)->first();
    if ($existingUser) {
        return response()->json([
            'success' => false,
            'message' => 'User already exists.',
        ], 200);
    }

    $user = new User;
    $user->email = $email;
    $user->name = $name;
    $user->mobile = $mobile;
    $user->password = Hash::make($password);
    $user->refer_code = $referCode;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Registered successfully.',
        'data' => $user,
    ], 201);
}
    
  //userdetails
    public function user_details(Request $request)
    {    
        $user_id = $request->input('user_id');
        if(empty($user_id)){
            return response()->json([
                'success'=>false,
                'message' => 'User Id is Empty',
            ], 200);
        }
        $user = User::where('id', $request->input('user_id'))->get();
        if (count($user)>=1) {
            return response()->json([
               "success" => true ,
                'message' => 'Details Retrieved Successfully',
                'data' =>$user,
            ], 201);
        }
        else{
            return response()->json([
                    "success" => false ,
                    'message'=> "User Not Found",
                  ], 400);
        }
    }

// course update details
public function update_course(Request $request)
{
    $course_id = $request->input('course_id');
    if (empty($course_id)) {
        return response()->json([
            'success' => false,
            'message' => 'Course ID is empty',
        ], 400);
    }

    $course = Course::find($course_id);

    if (!$course) {
        return response()->json([
            'success' => false,
            'message' => 'Course not found',
        ], 404);
    }

    $name = $request->input('name');
    $image = $request->file('image');

    if (!empty($name)) {
        $course->name = $name;
    }

    if (!empty($image)) {
        // Assuming you have a valid image upload logic
        $imagePath = Helpers::upload('course/', 'png', $image);
        $course->image = $imagePath;
    }

    $course->save();

    $courseDetails = [
        'id' => $course->id,
        'name' => $course->name,
        'image' => asset('storage/app/public/course/' . $course->image),
    ];

    return response()->json([
        'success' => true,
        'message' => 'Course details updated successfully',
        'data' => $courseDetails,
    ], 200);
}


    //app_update
    public function app_update(Request $request)
{
    $app_updates = app_update::all(); // Assuming 'AppUpdate' is the correct model name

    if ($app_updates->isEmpty()) {
        return response()->json([
            "success" => false,
            'message' => "App Updates Not Found",
        ], 404);
    }

    $app_updateDetails = $app_updates->toArray();

    return response()->json([
        "success" => true,
        'message' => 'App Updates Retrieved Successfully',
        'data' => $app_updateDetails,
    ], 200);
}
    
    
    
//courselist
public function course_list(Request $request)
{
    $courses = Course::all();

    if ($courses->isEmpty()) {
        return response()->json([
            "success" => false,
            'message' => "No courses found",
        ], 404);
    }

    $responseData = [];

    foreach ($courses as $course) {
        $courseDetails = $course->toArray();

        $responseData[] = [
            'id' => $courseDetails['id'],
            'author' => $courseDetails['author'],
            'course_title' => $courseDetails['course_title'],
            'image' => asset('storage/app/public/course/' . $courseDetails['image']),
        ];
    }

    return response()->json([
        "success" => true,
        'message' => 'Courses listed successfully',
        'data' => $responseData,
    ], 200);
}

//sessionlist
public function session_list(Request $request)
{
    $course_id = $request->input('course_id');

    if (empty($course_id)) {
        return response()->json([
            'success' => false,
            'message' => 'Course ID is empty',
        ], 400);
    }

    $sessions = Session::where('course_id', $course_id)->get();

    $responseData = [];

    foreach ($sessions as $session) {
        $sessionDetails = $session->toArray();

        $responseData[] = [
            'id' => $sessionDetails['id'],
            'title' => $sessionDetails['title'],
            'video_link' => $sessionDetails['video_link'],
            'video_duration' => $sessionDetails['video_duration'],
        ];
    }

    return response()->json([
        "success" => true,
        'message' => 'session listed successfully',
        'data' => $responseData,
    ], 200);
}
//my course list
//sessionlist
public function my_course_list(Request $request)
{
    $user_id = $request->input('user_id');

    if (empty($user_id)) {
        return response()->json([
            'success' => false,
            'message' => 'User ID is empty',
        ], 400);
    }

    $courses = Course::where('user_id', $user_id)->get();

    $responseData = [];

    foreach ($courses as $course) {
        $courseDetails = $course->toArray();

        $responseData[] = [
            'id' => $courseDetails['id'],
            'author' => $courseDetails['author'],
            'course_title' => $courseDetails['course_title'],
            'image' => asset('storage/app/public/course/' . $courseDetails['image']),
        ];
    }
    return response()->json([
        "success" => true,
        'message' => 'course listed successfully',
        'data' => $responseData,
    ], 200);
}


/*public function enrolled_course(Request $request)
{
    $user_id = $request->input('user_id');
    $course_id = $request->input('course_id');
    $enroll_date = $request->input('enroll_date');
 
    if (empty($user_id)) {
        return response()->json([
            'success' => false,
            'message' => 'user_id is empty',
        ], 200);
    }
    if (empty($course_id)) {
        return response()->json([
            'success' => false,
            'message' => 'course_id is empty',
        ], 200);
    }
    if (empty($enroll_date)) {
        return response()->json([
            'success' => false,
            'message' => 'enroll_date is empty',
        ], 200);
    }


    // Check if the enrollment already exists
    $enrolled_course = enrolled_course::where('user_id', $user_id)
                                      ->where('course_id', $course_id)
                                      ->first();

    if ($enrolled_course) {
        // Enrollment already exists
        return response()->json([
            'success' => false,
            'message' => ' already enrolled ',
        ], 200);
    } else {
        // Enrollment doesn't exist, create a new entry
        $enrolled_course = new enrolled_course();
        $enrolled_course->user_id = $user_id;
        $enrolled_course->course_id = $course_id;
        $enrolled_course->enroll_date = $enroll_date;
        $enrolled_course->save();

        return response()->json([
            'success' => true,
            'message' => 'enrolled_course added successfully',
            'data' => $enrolled_course,
        ], 201);
    }
}
//my enrolled_course
public function my_enrolled_course(Request $request)
{
    $user_id = $request->input('user_id');

    if (empty($user_id)) {
        return response()->json([
            'success' => false,
            'message' => 'user_id is empty',
        ], 400);
    }

    $user = User::find($user_id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ], 404);
    }


    $enrolled_courses = enrolled_course::where('user_id', $user_id)
        ->join('course', 'enrolled_course.course_id', '=', 'course.id')
        ->select('course.id', 'course.name', 'course.image')
        ->get();

    if ($enrolled_courses->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => ' enrolled courses not found for the user',
        ], 200);
    }

    $data = $enrolled_courses->map(function ($enrolled_course) {
        return [
            'id' => $enrolled_course->id,
            'name' => $enrolled_course->name,
            'image' => asset('storage/app/public/course/' . $enrolled_course->image),
        ];
    });

    return response()->json([
        'success' => true,
        'message' => 'Enrolled courses fetched successfully',
        'data' => $data,
    ], 200);
}*/
}
