<?php


namespace Ghost\GoogleAuthenticator\Facades;


use Illuminate\Support\Facades\Facade;

class GoogleAuthenticator extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'GoogleAuthenticatorService';
	}
}