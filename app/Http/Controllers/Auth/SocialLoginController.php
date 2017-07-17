<?php

namespace ChaseH\Http\Controllers\Auth;

use ChaseH\Models\User;
use ChaseH\Notifications\NoDemopgrahics;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function __construct() {
        $this->middleware(['social', 'guest']);
    }

    public function redirect($service, Request $request) {
        return Socialite::with($service)->redirect();
    }

    public function callback($service, Request $request) {
        $serviceUser = Socialite::driver($service)->user();

        $user = $this->getExistingUser($serviceUser, $service);

        if(!$user) {
            $user = User::create([
                'name' => $serviceUser->getName(),
                'handle' => str_slug(uniqid($serviceUser->getName())),
                'email' => $serviceUser->getEmail(),
                'password' => null,
            ]);

            $user->notify(new NoDemopgrahics());
        }

        if($this->needsToCreateSocial($user, $service)) {
            $user->services()->create([
                'social_id' => $serviceUser->getId(),
                'service' => $service,
            ]);
        }

        if($user->deleted_at !== null) {
            return redirect(route('home'))->withDanger("Sorry. Your account is locked.");
        }

        Auth::login($user); // Log in, don't remember;

        return redirect()->intended('/');
    }

    protected function getExistingUser($serviceUser, $service) {
        return User::where('email', $serviceUser->getEmail())->orWhereHas('services', function($query) use ($serviceUser, $service) {
            $query->where('social_id', $serviceUser->getId())->where('service', $service);
        })->withTrashed()->first();
    }

    protected function needsToCreateSocial(User $user, $service) {
        return !$user->hasSocialLinked($service);
    }
}
