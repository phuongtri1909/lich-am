<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{

    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pages.accounts.index', compact('users'));
    }

    public function create()
    {

        return view('admin.pages.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean'
        ],[
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không khớp.',
            'avatar.image' => 'Ảnh phải là định dạng ảnh.',
            'avatar.mimes' => 'Ảnh phải là định dạng ảnh.',
            'avatar.max' => 'Ảnh phải có dung lượng nhỏ hơn 2MB.',
            'active.boolean' => 'Trạng thái phải là boolean.',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'user';
        $data['active'] = $request->has('active') ? 1 : 0;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Tài khoản đã được tạo thành công.');
    }

    public function edit(User $account)
    {

        return view('admin.pages.accounts.edit', compact('account'));
    }

    public function update(Request $request, User $account)
    {

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $account->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'boolean'
        ], [
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu và xác nhận mật khẩu không khớp.',
            'avatar.image' => 'Ảnh phải là định dạng ảnh.',
            'avatar.mimes' => 'Ảnh phải là định dạng ảnh.',
            'avatar.max' => 'Ảnh phải có dung lượng nhỏ hơn 2MB.',
            'active.boolean' => 'Trạng thái phải là boolean.',
        ]);

        $data = $request->all();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $data['active'] = $request->has('active') ? 1 : 0;

        if ($request->hasFile('avatar')) {
            if ($account->avatar) {
                Storage::disk('public')->delete($account->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Tài khoản đã được cập nhật thành công.');
    }

    public function destroy(User $account)
    {

        $currentUser = auth()->user();
        if ($account->id === $currentUser->id) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'Bạn không thể xóa chính tài khoản của mình.');
        }

        if ($account->avatar) {
            Storage::disk('public')->delete($account->avatar);
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Tài khoản đã được xóa thành công.');
    }

    private function canEditUser($currentUser, $targetUser, $adminEmail)
    {
        if ($currentUser->email === $adminEmail) {
            return true;
        }

        return $targetUser->email !== $adminEmail;
    }

    private function canDeleteUser($currentUser, $targetUser, $adminEmail)
    {
        if ($currentUser->email === $adminEmail) {
            return true;
        }

        return $targetUser->email !== $adminEmail;
    }
}
