<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\course;
use App\Model\categories;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class courseController extends Controller
{
    public function index()
{
    $categoriess = categories::all(); // Fetch all courses from the database
    
    return view('admin-views.course.index', compact('categories'));
}

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $course = course::where(function ($q) use ($key) {
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
        }else{
            $course = new course();
        }

        $courses = $course->with('categories')->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.course.list', compact('courses', 'search'));
    }

    // public function search(Request $request)
    // {
    //     $key = explode(' ', $request['search']);
    //     $course = course::where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%")
    //                 ->orWhere('mobile', 'like', "%{$value}%")
    //                 ->orWhere('email', 'like', "%{$value}%")
    //                 ->orWhere('vehicle_number', 'like', "%{$value}%")
    //                 ->orWhere('pincode', 'like', "%{$value}%");
    //         }
    //     })->get();
    //     return response()->json([
    //         'view' => view('admin-views.course.partials._table', compact('courses'))->render()
    //     ]);
    // }


    public function preview($id)
    {
        $course = course::where(['id' => $id])->first();
        return view('admin-views.course.view', compact('course'));
    }

    public function store(Request $request)
    {
        

        $course = new course();
        $course->image = Helpers::upload('course/', 'png', $request->file('image'));
        $course->name = $request->name;
        $course->category_id = $request->category_id;
        $course->save();

        Toastr::success(translate('course added successfully!'));
        return redirect('admin/course/list');
    }

    public function edit($id)
{
    $course = course::find($id);
    $categoriess = categories::pluck('name', 'id'); // Fetch all courses as options for the dropdown
    return view('admin-views.session.edit', compact('course', 'categories'));
}


    public function update(Request $request, $id)
    {
       

        $course = course::find($id);
        $course->image = $request->has('image') ? Helpers::update('course/', $course->image, 'png', $request->file('image')) : $course->image;
        $course->name = $request->name;
        $course->category_id = $request->category_id;
        $course->status = $request->status;
        $course->save();

        Toastr::success(translate('course Details updated successfully!'));
        return redirect('admin/course/list');
    }

    public function delete(Request $request)
    {
        $course = course::find($request->id);
        if (Storage::disk('public')->exists('course/' . $course['image'])) {
            Storage::disk('public')->delete('course/' . $course['image']);
        }
        $course->delete();
        Toastr::success(translate('course Removed Successfully!'));
        return back();
    }
}
