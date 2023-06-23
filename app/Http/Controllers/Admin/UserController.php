<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Comment;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
class UserController extends Controller
{
    public function index()
    {
        return view('admin-views.user.index');
    }

    public function list(Request $request)
    {
        $search = $request->input('search');
        $users = User::query();

        if ($search) {
            $users->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('mobile', 'like', "%$search%")
                    ->orWhere('password', 'like', "%$search%");
            });
        }

        $users = $users->latest()->paginate(Helpers::getPagination());

        return view('admin-views.user.list', compact('users', 'search'));
    }

    public function preview($id)
    {
        $user = User::findOrFail($id);
        return view('admin-views.user.view', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'joined_date' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = $request->password;
        $user->joined_date = $request->joined_date;
        $user->status = $request->status;
        $user->save();

        Toastr::success(translate('User added successfully!'));
        return redirect('admin/user/list');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin-views.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'joined_date' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = $request->password;
        $user->joined_date = $request->joined_date;
        $user->status = $request->status;
        $user->save();

        Toastr::success(translate('User details updated successfully!'));
        return redirect('admin/user/list');
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();

        Toastr::success(translate('User deleted successfully!'));
        return back();
    }
}
