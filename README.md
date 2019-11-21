Google 两步登陆验证
======

1. 相对于验证码，安全很多；几乎是不会存在破解的方法
1. 验证码有时候无法识别，不方便操作
1. 一机一码，不会存在账号盗用的问题
1. 动态验证，每30秒生产一个验证码，安全更加保障



```php
'providers' => [
    //........
    Earnp\GoogleAuthenticator\GoogleAuthenticatorServiceprovider::class,
    SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
],

'aliases' => [
     //..........
    'Google' => Earnp\GoogleAuthenticator\Facades\GoogleAuthenticator::class,
    'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class
],
```

服务注入以后，如果要使用自定义的配置，还可以发布配置文件到config/views目录：
```php
php artisan vendor:publish --provider="Earnp\GoogleAuthenticator\GoogleAuthenticatorServiceprovider"
```

添加 后台用户表参数

php artisan migrate

