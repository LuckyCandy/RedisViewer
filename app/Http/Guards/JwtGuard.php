<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-05-21
 * Time: 12:14
 */

namespace App\Http\Guards;


use App\Classes\Jwt;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class JwtGuard extends TokenGuard
{
    /**
     * New token expire time (second)
     *
     * @var int
     */
    protected $expire;

    /**
     * JwtGuard constructor.
     * @param UserProvider $provider
     * @param Request $request
     * @param string $inputKey
     * @param string $storageKey
     * @param bool $hash
     * @param int $expire
     */
    public function __construct(UserProvider $provider, Request $request, string $inputKey = 'api_token', string $storageKey = 'api_token', bool $hash = false, $expire = 7200)
    {
        $this->expire = $expire;
        parent::__construct($provider, $request, $inputKey, $storageKey, $hash);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function attempt(array $credentials = [])
    {

        $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user);

            return true;
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        // $this->fireFailedEvent($user, $credentials);

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        $validated = ! is_null($user) && $this->provider->validateCredentials($user, $credentials);

        return $validated;
    }

    /**
     * Log a user into the application.
     *
     * @param  Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {

        $user->jwt = Jwt::make([
            $user->getAuthIdentifierName() => $user->getAuthIdentifier()
        ],  $this->expire);

        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        Event::dispatch(new Login('jwt', $user, $remember));

        $this->setUser($user);
    }

    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (! empty($token) && $credential = Jwt::getPayloadFrom($token)) {
            $user = $this->provider->retrieveByCredentials($credential);
        }

        return $this->user = $user;
    }
}