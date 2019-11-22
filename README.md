Google 两步登陆验证
======

1. 相对于验证码，安全很多；几乎是不会存在破解的方法
1. 验证码有时候无法识别，不方便操作
1. 一机一码，不会存在账号盗用的问题
1. 动态验证，每30秒生产一个验证码，安全更加保障

> composer require ghost/google-authenticator
> php artisan migrate
```php
'google-authenticator' => [
		    'enable' => true,
		    'authenticatorname' => '' //名称 

	    ],
```



