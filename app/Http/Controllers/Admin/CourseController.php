<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Categories;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $categories = Categories::all(); // Fetch all categories from the database
        return view('admin-views.course.index', compact('categories'));
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $courses = Course::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('sub_name', 'like', "%{$value}%")
                        ->orWhere('sub_code', 'like', "%{$value}%")
                        ->orWhere('department', 'like', "%{$value}%")
                        ->orWhere('year', 'like', "%{$value}%")
                        ->orWhere('publication', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $courses = new Course();
        }

        $courses = $courses->with('categories')->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.course.list', compact('courses', 'search'));
    }

    public function preview($id)
    {
        $course = Course::findOrFail($id);
        return view('admin-views.course.view', compact('course'));
    }

    public function store(Request $request)
    {
        $course = new Course();
        $course->image = Helpers::upload('course/', 'png', $request->file('image'));
        $course->name = $request->name;
        $course->category_id = $request->category_id;
        $course->save();

        Toastr::success(translate('course added successfully!'));
        return redirect('admin/course/list');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $categories = Categories::pluck('name', 'id'); // Fetch all categories as options for the dropdown
        return view('admin-views.course.edit', compact('course', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->image = $request->has('image') ? Helpers::update('course/', $course->image, 'png', $request->file('image')) : $course->image;
        $course->name = $request->name;
        $course->status = $request->status;
        $course->category_id = $request->category_id;
        $course->save();

        Toastr::success(translate('course Details updated successfully!'));
        return redirect('admin/course/list');
    }

    public function delete(Request $request)
    {
        $course = Course::findOrFail($request->id);
        if (Storage::disk('public')->exists('course/' . $course['image'])) {
            Storage::disk('public')->delete('course/' . $course['image']);
        }
        $course->delete();
        Toastr::success(translate('course Removed Successfully!'));
        return back();
    }
}
