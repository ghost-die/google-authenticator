<?php


namespace Ghost\GoogleAuthenticator\Facades;


use Illuminate\Support\Facades\Facade;

class GoogleAuthenticatorService extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'GoogleAuthenticatorService';
	}
}