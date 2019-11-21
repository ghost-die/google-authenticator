<?php

namespace Ghost\GoogleAuthenticator;

use Encore\Admin\Extension;

class GoogleAuthenticator extends Extension
{
    public $name = 'google-authenticator';

    public $views = __DIR__.'/../resources/views';

//    public $assets = __DIR__.'/../resources/assets';
//
//    public $menu = [
//        'title' => 'Googleauthenticator',
//        'path'  => 'google-authenticator',
//        'icon'  => 'fa-gears',
//    ];
}