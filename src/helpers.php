<?php

if (!function_exists('google_check_code')) {
	/**
	 * @param string $secret
	 * @param string $oneCode
	 * @param int $discrepancy
	 * @return bool
	 */
    function google_check_code(string $secret,string $oneCode,int $discrepancy=1)
    {
    	$checkResult = app('GoogleAuthenticator')->verifyCode($secret,$oneCode, $discrepancy);//对传入的参数进行校验
	    if ($checkResult) return true;//校验成功
	    return false;//校验失败
    }
}

if (!function_exists('google_create_secret')) {
	/**
	 * @param int $secretLength
	 * @param string $secret
	 * @return array
	 */
	function google_create_secret(int $secretLength = 16,string $secret='')
	{
		if (''===$secret)
		{
			$secret = app('GoogleAuthenticator')->createSecret($secretLength);//创建一个Secret
		}
		
		
		$config['authenticatorname'] =urlencode(config('admin.extensions.google-authenticator.authenticatorname')) ;
		$qrCodeUrl="otpauth://totp/".$config['authenticatorname']."?secret=".$secret;//二维码中填充的内容
		$googlesecret = array('secret' =>$secret ,'codeurl'=>$qrCodeUrl);
		
		return $googlesecret;
	}
}
