<?php

namespace Core\Auth;

use Core\Session\Session;
use Core\Database\Connection;

class Authenticate
{
    protected ?object $user = null;
    protected string $guard = 'web';
    protected string $model;
    protected string $driver;
    protected string $provider;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->loadConfig()->setUser();
    }

    /**
     * @throws \Exception
     */
    protected function loadConfig(): self
    {
        $this->driver   = config("auth.guards.$this->guard.driver");
        $this->provider = config("auth.guards.$this->guard.provider");
        $this->model    = config("auth.providers.$this->provider.model");

        if (!$this->driver || !$this->provider || !$this->model) {
            throw new \Exception('Invalid authentication configuration');
        }

        return $this;
    }

    protected function setUser(): self
    {
        $this->user = match ($this->driver) {
            'session' => $this->getUserFromSession(),
            'token'   => $this->getUserFromToken(),
            default   => null,
        };

        return $this;
    }

    protected function getUserFromSession()
    {
        $userId = Session::get('user_id');

        if ($userId) {
            $user = $this->userModel()->find($userId);
            Session::put('user', $user);
            return $user;
        }

        return null;
    }

    protected function getUserFromToken()
    {
        $token = request()->bearerToken();

        if ($token) {
            $user = $this
                ->userModel()
                ->join('personal_access_tokens as tokens', function ($join) {
                    $join->on('users.id', '=', 'tokens.tokenable_id')
                         ->where('tokens.tokenable_type', $this->model);
                })
                ->where('personal_access_tokens.token', $token)
                ->first();

            if ($user) {
                Session::put('user', $user);
            }

            return $user;
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    public function guard($name = null)
    {
        if ($name) {
            $this->guard = $name;
            $this->loadConfig()->setUser();
        }
        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    public function guest()
    {
        return is_null($this->user);
    }

    public function check()
    {
        return !is_null($this->user) && $this->model::find($this->user->id);
    }

    public function id()
    {
        return $this->user?->id ?? null;
    }

    public function attempt(array $credentials)
    {
        $user = $this->model::where('email', $credentials['email'])->first();

        if ($user && password_verify($credentials['password'], $user->password)) {
            $this->login($user);
            return true;
        }

        return false;
    }

    public function login($user)
    {
        $this->user = $user;
        Session::put('user_id', $user->id);
        Session::put('user', $user);
    }

    public function logout()
    {
        $this->user = null;
        Session::forget('user_id');
        Session::forget('user');
    }

    protected function userModel()
    {
        return new $this->model();
    }
}
