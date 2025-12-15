<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {    
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:8'],
           'address' => ['nullable', 'string', 'max:255'],
        ]);

    try {
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'raw_password' => $request->password,
        'company_name' => $request->company_name,
        'phone_number' => $request->phone_number,
        'postal_code' => $request->postal_code,
        'address' => $request->address,
        'role' => 'user',
    ]);
        return redirect()->route('auth.list')->with('success', 'ユーザーが登録されました。');
        } catch (\Exception $e) {
        dd('登録失敗', $e->getMessage());
        }

       
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function list(): View
    {
        $users = User::all();
        return view('auth.list', ['users' => $users]);
    }

    public function detail($id): View
    {
        $user = User::findOrFail($id);
        return view('auth.detail', ['user' => $user]);
    }

    public function edit($id):View
    {
         $user = User::findOrFail($id);
         return view('auth.edit',['user'=>$user]);
    }
    public function update(Request $request, $id): RedirectResponse
        {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$id],
        'password' => ['nullable', Rules\Password::defaults()],
        'company_name' => ['required', 'string', 'max:255'],
        'phone_number' => ['nullable', 'string', 'max:20'],
        'postal_code' => ['nullable', 'string', 'max:8'],
        'address' => ['nullable', 'string', 'max:255'],
    ]);

    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->company_name = $request->company_name;
    $user->phone_number = $request->phone_number;
    $user->postal_code = $request->postal_code;
    $user->address = $request->address;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
        $user->raw_password = $request->password;
    }

    $user->save();

    return redirect()->route('auth.detail', ['id' => $user->id])->with('status', '更新しました');
    }

     public function delete_confirm($id): View
    {
        $user = User::findOrFail($id);
        return view('auth.confirm',['user'=>$user]);
        
    }
    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->delete();
        return redirect()->route('auth.list')->with('success', 'ユーザー情報が削除されました。');    
    
    }
    
}
