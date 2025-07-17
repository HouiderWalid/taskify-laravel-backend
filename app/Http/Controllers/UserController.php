<?php

namespace App\Http\Controllers;

use App\Exceptions\FrontException;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function signIn(Request $request)
    {
        return $this->tryInUnrestrictedContext($request, function (Request $request) {

            $validationRules = [
                'email' => 'required|email',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return $this->apiResponse(401, [], $validator->errors());
            }

            $email = $request->input('email');
            $password = $request->input('password');
            $user = User::where(User::getEmailAttributeName(), $email)
                ->first();

            if (!($user instanceof User)) {
                throw new FrontException('Wrong credentials.');
            }

            $isPasswordCorrect = Hash::check($password, $user->getPassword());
            if (!$isPasswordCorrect) {
                throw new FrontException('Wrong credentials');
            }

            $token = $user->createToken(config('app.name'))->plainTextToken ?? null;

            return $this->apiResponse(200, [
                'token' => $token,
                'user' => $user
            ]);
        });
    }

    public function signUp(Request $request)
    {
        return $this->tryInUnrestrictedContext($request, function (Request $request) {

            $validationRules = [
                'full_name' => 'required|min:3|max:50',
                'email' => ['required', 'email', 'max:255', User::uniqueAt(User::getEmailAttributeName())],
                'password' => 'required',
                'password_confirmation' => 'required',
            ];

            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return $this->apiResponse(401, [], $validator->errors());
            }

            $fullName = $request->input('full_name');
            $email = $request->input('email');
            $password = $request->input('password');

            $role = Role::where(Role::getNameAttributeName(), Role::MEMBER_ROLE)
                ->first();

            if (!($role instanceof Role)) {
                throw new Exception('Role not found.');
            }

            $user = User::create([
                User::getFullNameAttributeName() => $fullName,
                User::getRoleIdAttributeName() => $role->getId(),
                User::getEmailAttributeName() => $email,
                User::getPasswordAttributeName() => Hash::make($password),
            ]);

            if (!($user instanceof User)) {
                throw new FrontException('User Creation Failure.');
            }

            $token = $user->createToken(config('app.name'))->plainTextToken ?? null;

            return $this->apiResponse(200, [
                'token' => $token,
                'user' => $user
            ]);
        });
    }

    public function getAuthUser(Request $request)
    {
        return $this->tryInUnrestrictedContext($request, function (Request $request, ?User $user) {

            if (!($user instanceof User)) {
                throw new Exception("User not found");
            }

            return $this->apiResponse(200, $user);
        });
    }
}
