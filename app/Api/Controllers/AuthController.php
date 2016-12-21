<?php

namespace App\Api\Controllers;

use App\Api\Requests\LoginRequest;
use App\Api\Transformers\UserTransformer;
use App\Client;
use App\Notifications\UserCreationConfirmation;
use App\User;
use Dingo\Blueprint\Annotation\Method\Post;
use FTP;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Storage;

/**
 * Class AuthController
 * @package App\Http\Api\Controllers
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;
    
    /**
     * Попытка авторизации пользователя
     * Если упешно - возвращаются данные пользователя
     * Если не успешно - попытка найти пользователя с таким email иначе создание нового
     *
     * @Post("/login")
     * @Request({"email": "foo@bm.com", "password": "bar"})
     * @Response(200, body={"id": 10, "username": "foo", "email": "foo@bm.com", "created_at": "2016-09-08 09:18:24"})
     *
     * @param LoginRequest $request
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        if ($this->guard()->attempt($this->credentials($request))) {
            return $this->response()->item(auth()->user(), new UserTransformer)->addMeta('is_new', 0);
        } elseif ($user = User::whereEmail($request->get('email'))->first()) {
            return $this->response->error('Wrong password', 422);
        }
        
        $user = Client::create([
            'name'          => explode('@', $request->get('email'))[0],
            'email'         => $request->get('email'),
            'password'      => $request->get('password'),
            'role'          => User::ROLE_USER
        ]);
        $user->notify(new UserCreationConfirmation($request->get('password')));
        
        return $this->response->item($user, new UserTransformer)->addMeta('is_new', 1);
    }
}
