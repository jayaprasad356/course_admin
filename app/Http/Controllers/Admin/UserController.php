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
            'mobile' => 'required',
            'balance' => 'required',
            'referred_by' => 'required',
           
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->balance = $request->balance;
        $user->mobile = $request->mobile;
        $user->referred_by = $request->referred_by;
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
      

        $user = User::findOrFail($id);
        $user->mobile = $request->mobile;
        $user->joined_date = $request->joined_date;
        $user->earn = $request->earn;
        $user->balance = $request->balance;
        $user->device_id = $request->device_id;
        $user->referred_by = $request->referred_by;
        $user->refer_code = $request->refer_code;
        $user->withdrawal_status = $request->withdrawal_status;
        $user->status = $request->status;
        $user->min_withdrawal = $request->min_withdrawal;
        $user->account_num = $request->account_num;
        $user->holder_name = $request->holder_name;
        $user->bank = $request->bank;
        $user->branch = $request->branch;
        $user->ifsc = $request->ifsc;
        $user->basic_wallet = $request->basic_wallet;
        $user->premium_wallet = $request->premium_wallet;
        $user->total_ads = $request->total_ads;
        $user->today_ads = $request->today_ads;
        $user->target_refers = $request->target_refers;
        $user->current_refers = $request->current_refers;
        $user->gender = $request->gender;
        $user->support_lan = $request->support_lan;
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
