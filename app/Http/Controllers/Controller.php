<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Cookie;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function register(Request $request)
    {
           // ตรวจสอบ email ซ้ำก่อนการสร้างผู้ใช้
    if (User::where('email', $request->input('email'))->exists()) {
        return response()->json(['error' => 'อีเมลนี้มีอยู่แล้ว'], 400);
    }

    // สร้างผู้ใช้ในกรณีที่ไม่มีอีเมลซ้ำ
    $user = User::create([
        'name' => $request['name'],
        'lname' => $request['lname'],
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
    ]);

    return $user;

    }

    public function verifyLogin(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(['message' => 'Invalid Credentials', 'status' => 0],
                Response::HTTP_UNAUTHORIZED
            );}

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24); // 1 day
        
        return response([
            'email' => $request->email,
            'user_id' => $user->id,
            'jwt_token' => $token,
            'message' => 'Login Success',
            'status' => 1,
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function logout(Request $request)
    {
        $cookie = \Cookie::forget('jwt');
        return response(['message' => 'Logout Success'])->withCookie($cookie);
    }

    public function updatePassword(Request $request)
    {
        #Match The Old Password
        if (!Hash::check($request->old_passwcord, Auth::user()->password)) {
            return response(['message' => 'Old Password Doesnt match!'],400);
        }
        #Update the new Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);
        return response(['message' => 'Password changed successfully!']);
    }

    public function editUser(Request $request)
    {

        if (!isset($request['email'])) {
            $user = User::where('id',Auth::user()->id)
                ->update([
                    'name' => $request['fname'],
                    'lname' => $request['lname'],
                ]);
            if($user == 1){
                return response(['message' => 'update name successfully!']);
            }
           
        }
        if (!isset($request['fname']) || !isset($request['lname'])) {
            $user = User::where('id', Auth::user()->id)
                ->update([
                    'email' => $request['email'],
                ]);
                if($user == 1){
                    return response(['message' => 'update email successfully!']);
                }
        }
    }
    public function deleteUser(Request $request)
    {
        $user = User::find(Auth::user()->id);
        // $category->title = $request->title;
        $user->delete();
        return response(['message' => 'delete Success']);
    }

}
