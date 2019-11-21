<?php

namespace Ghost\GoogleAuthenticator;

use Illuminate\Support\ServiceProvider;

class GoogleAuthenticatorServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(GoogleAuthenticator $extension)
    {
        if (! GoogleAuthenticator::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'google-authenticator');
        }

        
        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/ghost/google-authenticator')],
                'google-authenticator'
            );
        }
	
        
	    if ($this->app->runningInConsole()) {
		    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
	    }

        $this->app->booted(function () {
            GoogleAuthenticator::routes(__DIR__.'/../routes/web.php');
        });
    }
}