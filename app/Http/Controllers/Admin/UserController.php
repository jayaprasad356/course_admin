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
            $query_param = [];
            $search = $request['search'];
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $users = User::where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('mobile', 'like', "%{$value}%")
                            ->orWhere('password', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            } else {
                $users = new User();
            }
        
            $users = $users->latest()->paginate(Helpers::getPagination())->appends($query_param);
            return view('admin-views.user.list', compact('users', 'search'));
        }
    
        // public function search(Request $request)
        // {
        //     $key = explode(' ', $request['search']);
        //     $book = book::where(function ($q) use ($key) {
        //         foreach ($key as $value) {
        //             $q->orWhere('name', 'like', "%{$value}%")
        //                 ->orWhere('mobile', 'like', "%{$value}%")
        //                 ->orWhere('email', 'like', "%{$value}%")
        //                 ->orWhere('vehicle_number', 'like', "%{$value}%")
        //                 ->orWhere('pincode', 'like', "%{$value}%");
        //         }
        //     })->get();
        //     return response()->json([
        //         'view' => view('admin-views.book.partials._table', compact('books'))->render()
        //     ]);
        // }
    
    
        public function preview($id)
        {
            $user = user::where(['id' => $id])->first();
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
            $user = new user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = $request->password;
            $user->joined_date = $request->joined_date;
            $user->status = $request->status;
            $user->save();
    
            Toastr::success(translate('user added successfully!'));
            return redirect('admin/user/list');
        }
    
        public function edit($id)
        {
            $user = user::find($id);
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
    
            ], [
                'name.required' => translate('name is required!'),
                'email.required' => translate('email is required!'),
                'mobile.required' => translate('mobile  is required!'),
                'password.required' => translate('password is required!'),
                'joined_date.required' => translate('joined_date is required!'),
    
            ]);
    
            $user = user::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = $request->password;
            $user->joined_date = $request->joined_date;
            $user->status = $request->status;
            $user->save();
    
            Toastr::success(translate('user Details updated successfully!'));
            return redirect('admin/user/list');
        }
    
        public function delete(Request $request)
        {
            $user = user::find($request->id);
            $user->delete();
            Toastr::success(translate('user Deleted Successfully!'));
            return back();
        }
    }



