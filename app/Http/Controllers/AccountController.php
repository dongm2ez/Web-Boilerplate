<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserConfig;
use App\Models\UserProfile;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AccountController extends Controller
{

    public function __construct()
    {
        // 执行 jwt.auth 认证
        $this->middleware('jwt.auth')->except(
            'validateInviteCode',
            'register',
            'login',
            'logout',
            'passwordReset'
        );
    }

    /**
     * 验证邀请码是否有效
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function validateInviteCode(Request $request)
    {
        $rules = [
            'invite_code' => 'required|exists:invite_codes,code,use_at,NULL',
        ];

        $this->validate($request, $rules);

        return $this->success();
    }

    public function register(Request $request)
    {
        $rules = [
            'mobile' => 'required|unique:users',
            'password' => 'required|confirmed|between:6,32',
        ];
        $this->validate($request, $rules);

        $inviteCode = $request->input('invite_code');
        $params = $request->only('mobile', 'password');
        $params['password'] = bcrypt($params['password']);

        $user = new User($params);

        $user->save();

        return $this->failure();
    }

    public function login(Request $request)
    {
        $rules = [
            'mobile' => 'required|exists:users',
            'password' => 'required|between:6,32',
        ];
        $this->validate($request, $rules);

        $credentials = $request->only('mobile', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->failure(trans('auth.failed'), 401);
            }

            $user = User::find(Auth::id());
            // 设置JWT令牌
            $user->jwt_token = [
                'access_token' => $token,
                'expires_in' => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp
            ];
            return $this->success($user);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->failure(trans('jwt.could_not_create_token'), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();
        } catch (TokenBlacklistedException $e) {
            return $this->failure(trans('jwt.the_token_has_been_blacklisted'), 500);
        } catch (JWTException $e) {
            // 忽略该异常（Authorization为空时会发生）
        }
        return $this->success();
    }

    public function getProfile(Request $request)
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            return $this->failure(trans('token_expired'), $e->getStatusCode());
        } catch (JWTException $e) {
            return $this->failure(trans('token_invalid'), $e->getStatusCode());
        }
        $data = User::with('userProfile')->find(Auth::id());
        $data->jwt_token = [
            'access_token' => $newToken,
            'expires_in' => Carbon::now()->addMinutes(config('jwt.ttl'))->timestamp
        ];
        return $this->success($data);
    }

    public function updateProfile(Request $request)
    {
        $params = $request->only('name', 'company', 'job', 'email', 'avatar_url', 'status', 'bio');

        $rules = [
            'name' => 'min:2|max:32',
            'email' => 'email',
            'avatar_url' => 'required',
        ];
        $this->validate($request, $rules);


        $user = UserProfile::whereUserId(Auth::id())->first();
        if ($user->update($params)) {
            return $this->success($user);
        }
        return $this->failure();
    }

    public function configs(Request $request)
    {
        $params = $request->all();
        foreach ($params as $key => $value) {
            $config = UserConfig::firstOrCreate([
                'user_id' => Auth::id(),
                'key' => $key,
            ]);
            $config->update(['value' => $value]);
        }
        $data = UserConfig::where('user_id', Auth::id())->get();
        if ($data) {
            return $this->success($data);
        }
        return $this->failure();
    }

    public function passwordReset(Request $request)
    {
        $mobile = $request->input('mobile');
        $verify_code = $request->input('verify_code');
        $new_password = $request->input('new_password');

        $rules = [
            'mobile' => 'required|exists:users',
            'verify_code' => 'required|between:4,6',
            'new_password' => 'required|between:6,32',
        ];
        $this->validate($request, $rules);

        $user = User::where('mobile', $mobile)->first();

        $new_password = bcrypt($new_password);
        if ($user->update(['password' => $new_password])) {
            return $this->success($user);
        }

        return $this->failure();
    }

    public function passwordModify(Request $request)
    {
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');

        $rules = [
            'old_password' => 'required|between:6,32',
            'new_password' => 'required|between:6,32',
        ];
        $this->validate($request, $rules);

        $credentials = array(
            'id' => Auth::id(),
            'password' => $old_password,
        );
        if (!Auth::attempt($credentials, true)) {
            return $this->failure('原密码错误。');
        }

        $new_password = bcrypt($new_password);

        $user = User::find(Auth::id());
        if ($user->update(['password' => $new_password])) {
            return $this->success($user);
        }
        return $this->failure();
    }

}
