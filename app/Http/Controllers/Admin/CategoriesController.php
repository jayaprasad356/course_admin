<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\categories;
use App\Model\Comment;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('admin-views.categories.index');
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $categories = categories::where(function ($q) use ($key) {
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
            $categories = new categories();
        }

        $categoriess = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        return view('admin-views.categories.list', compact('categoriess', 'search'));
    }

    public function preview($id)
    {
        $categories = categories::where(['id' => $id])->first();
        return view('admin-views.categories.view', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $categories = new categories();
        $categories->name = $request->name;
        $categories->save();

        Toastr::success(translate('categories added successfully!'));
        return redirect('admin/categories/list');
    }

    public function edit($id)
    {
        $categories = categories::find($id);
        return view('admin-views.categories.edit', compact('categories'));
    }

    public function update(Request $request, $id)
    {
        $categories = categories::find($id);
        $categories->name = $request->name;
        $categories->save();

        Toastr::success(translate('categories Details updated successfully!'));
        return redirect('admin/categories/list');
    }

    public function delete(Request $request)
    {
        $categories = categories::find($request->id);
        if (Storage::disk('public')->exists('categories/' . $categories['image'])) {
            Storage::disk('public')->delete('categories/' . $categories['image']);
        }
        $categories->delete();
        Toastr::success(translate('categories Removed Successfully!'));
        return back();
    }
}
