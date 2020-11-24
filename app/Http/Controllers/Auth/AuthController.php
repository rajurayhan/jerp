<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * Login user and create token
     * @group  Authentication
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam  email string required The name of email. Example: admin@domain.com
     * @bodyParam  password string required The name of password <b>(min:5,max:10).</b> Example: 12345678
     * @return \Illuminate\Http\Response
     * @response 200  {"status":"Success","message":null,"data":{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImVmM2FiM2VkZjUxMGQ3MTk3OTZjMGVjMDNhY2NjYWE1MzI5YmIxN2EzZDFiZTM0YjM0NTRkMDZkMzhjMzQyYThmZmEwZmExM2EzYTI3ZTNiIn0.eyJhdWQiOiIxIiwianRpIjoiZWYzYWIzZWRmNTEwZDcxOTc5NmMwZWMwM2FjY2NhYTUzMjliYjE3YTNkMWJlMzRiMzQ1NGQwNmQzOGMzNDJhOGZmYTBmYTEzYTNhMjdlM2IiLCJpYXQiOjE2MDYyMzM0OTksIm5iZiI6MTYwNjIzMzQ5OSwiZXhwIjoxNjA2MzE5ODk5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.QwcjCZS_dio0jOOiAs9JcfVmI6SR5Fd4mo3Tjc60M41OjUAhHbzfi-DK1Nt2B066VoF2ymadztFmvLVD1Zgopqfkf_urN3mPCYK7AvgnzW_uv32u6Aos3IBhF71tI59M5JaKaMNlOnNhvnrna-sODcZLnECP--5rNul8Jm61H2lGZW4DHv37w6JOwuL82MEjPTp47gl2oJakTr5HB1SCepK8fzDS3wGVxbanhDdySefVRyv6JVGbj4OXYrFx_vuhKd1iL9xB8V-Bf6-wfKH4A1o_5Hy0xxtgpnEJ0dyrF5gg_QxmQCjIjcIefqd_HJY94cAT6589jXJxk36C-awAbSaC3_vB-42SmImq7CbyQusR8irSfWZNbpzSE35QrQK1OLyaSfcAxnx3V1hkqZQkZ2lzYD54jf4lhHNb1Wvpiko0O2omOXPOQdYAbnoDpXAdRwL05phVZR-Y579HnKv9ONnjfVMrcXYAiB_Bnik8KxNYxoy2t00cTcKLABWI6eL558Zot8cuEQm0W5a72J0YzDZrkFnW_BXzOWGU8i5kHVIICP2PyCB3LD4pV3ImFU9ej6Yxb9WvajFp-_M-x_FPB5mXqtE2kojQhTd53dtKo4y_8Ba5Lu1DvNWqP3AiFwkJ-_RbqYflqLl5a1aPa3RA0G4piZge7iPD7tLLR6VdygA","token_type":"Bearer","expires_at":"2020-11-25 15:58:19"}}
     */

    public function login(Request $request)
    {
        $attr = $this->validateLogin($request);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials mismatch', 401);
        }

        return $this->token($this->getPersonalAccessToken());
    }

    public function signup(Request $request)
    {
        $attr = $this->validateSignup($request);

        User::create([
            'name' => $attr['name'],
            'email' => $attr['email'],
            'password' => Hash::make($attr['password'])
        ]);

        Auth::attempt(['email' => $attr['email'], 'password' => $attr['password']]);

        return $this->token($this->getPersonalAccessToken(), 'User Created', 201);
    }

    /**
     * Get Loggedin User Info
     * @group  Authentication
     * @param  \Illuminate\Http\Request  $request
     * @response 200  {"status":"success","message":"User Informations","code":200,"data":{"id":1, "email":"admin@domain.com","email_verified_at":"2020-09-13T08:51:45.000000Z","status":1,"deleted_at":null,"created_at":"2020-09-13T08:51:45.000000Z","updated_at":"2020-09-13T08:51:45.000000Z"}}
     */

    public function user()
    {
        return $this->success(Auth::user());
    }

    /**
     * Logout user (Revoke the token)
     * @group  Authentication
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @response 200  {"status":"success","message":"Logout Success","code":200,"data":[[]]}
     */

    public function logout()
    {
        Auth::user()->token()->revoke();
        return $this->success('User Logged Out', 200);
    }

    public function getPersonalAccessToken()
    {
        if (request()->remember_me === 'true')
            Passport::personalAccessTokensExpireIn(now()->addDays(15));

        return Auth::user()->createToken('Personal Access Token');
    }

    public function validateLogin($request)
    {
        return $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    public function validateSignup($request)
    {
        return $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
}
