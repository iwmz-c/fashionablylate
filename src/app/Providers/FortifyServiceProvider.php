<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        /*Fortify::createUsersUsing(CreateNewUser::class);
        
        Fortify::registerView(function () {
            return view('auth.register');
        });*/

        Fortify::loginView(function () {
            return view('auth.login');
        });

        /*Fortify::authenticateUsing(function (Request $request) {
            $loginRequest = LoginRequest::createFromBase($request)
            ->setContainer(app())
            ->setRedirector(app('redirect'));
            $loginRequest->validateResolved();

            \Validator::make(
                $loginRequest->all(),
                $loginRequest->rules(),
                $loginRequest->messages()
            )->validate();

            $user = User::where('email', $loginRequest->input('email'))->first();
            if ($user && Hash::check($loginRequest->input('password'), $user->password)) {
                return $user;
            }

            return null;
        });*/

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
