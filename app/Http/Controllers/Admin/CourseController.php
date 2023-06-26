<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\course;
use App\Model\Categories;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        return view('admin-views.course.index', compact('categories'));
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request->search;

        if ($request->has('search')) {
            $key = explode(' ', $request->search);
            $course = Course::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('sub_name', 'like', "%{$value}%")
                        ->orWhere('sub_code', 'like', "%{$value}%")
                        ->orWhere('department', 'like', "%{$value}%")
                        ->orWhere('year', 'like', "%{$value}%")
                        ->orWhere('publication', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request->search];
        } else {
            $course = Course::query();
        }
        $courses = $course->with('categories')->latest()->paginate(Helpers::getPagination())->appends($query_param);
        $totalCourses = $courses->total();

        return view('admin-views.course.list', compact('courses', 'search'));
    }


    public function preview($id)
    {
        $course = Course::where('id', $id)->first();
        return view('admin-views.course.view', compact('course'));
    }

    public function store(Request $request)
    {
        $course = new Course();
        $course->image = Helpers::upload('course/', 'png', $request->file('image'));
        $course->author = $request->author;
        $course->category_id = $request->category_id;
        $course->save();

        Toastr::success(translate('Course added successfully!'));
        return redirect('admin/course/list');
    }

    public function edit($id)
    {
        $course = Course::find($id);
        $categories = Categories::pluck('name', 'id');
        return view('admin-views.course.edit', compact('course', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        $course->image = $request->has('image') ? Helpers::update('course/', $course->image, 'png', $request->file('image')) : $course->image;
        $course->author = $request->author;
        $course->category_id = $request->category_id;
        $course->status = $request->status;
        $course->save();

        Toastr::success(translate('Course details updated successfully!'));
        return redirect('admin/course/list');
    }

    public function delete(Request $request)
    {
        $course = Course::find($request->id);
        if (Storage::disk('public')->exists('course/' . $course->image)) {
            Storage::disk('public')->delete('course/' . $course->image);
        }
        $course->delete();
        Toastr::success(translate('Course removed successfully!'));
        return back();
    }
}
