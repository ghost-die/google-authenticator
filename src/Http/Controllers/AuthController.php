<?php

namespace Ghost\GoogleAuthenticator\Http\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\AuthController as BaseAuthController;

use Illuminate\Support\Facades\Validator;

class AuthController extends BaseAuthController
{
	
	protected $loginView = 'google-authenticator::login';
	
	protected $googleView = 'google-authenticator::google';
	
	/**
	 * {@inheritdoc}
	 */
	public function getLogin()
	{
		if ($this->guard()->check()) {
			return redirect($this->redirectPath());
		}
		
		return view($this->loginView);
	}
	
	/**
	 * Handle a login request.
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function postLogin(Request $request)
	{
		$this->loginValidator($request->all())->validate();
		
		
		$admin = Administrator::query()->where(['username'=>$request->get($this->username())]);
		$google = $admin->value('google_auth');
		
		$is_open_google_auth = $admin->value('is_open_google_auth');
		
		if ($is_open_google_auth){
			
			$onecode = (string) $request->get('onecode');
			
			
			if (empty($onecode) && strlen($onecode) != 6)
			{
				$request->flash();
				return back()->withErrors(['onecode'=>'Google 验证码错误']);
			}
			
			
			if(!google_check_code((string)$google,$onecode,1)) {
				// 绑定场景：绑定成功，向数据库插入google参数，跳转到登录界面让用户登录
				// 登录认证场景：认证成功，执行认证操作
				$request->flash();
				return back()->withErrors(['onecode'=>'Google 验证码错误']);
			}
		}
		
		
		$credentials = $request->only([$this->username(), 'password']);
		$remember = $request->get('remember', false);
		
		if ($this->guard()->attempt($credentials, $remember)) {
			return $this->sendLoginResponse($request);
		}
		
		return back()->withInput()->withErrors([
			$this->username() => $this->getFailedLoginMessage(),
		]);
	}
	
	public function google()
	{
		
		if (auth('admin')->user()->google_auth){
			
			$secret = auth('admin')->user()->google_auth;
			$qrCodeUrl="otpauth://totp/".config("google.authenticatorname")."?secret=".$secret;//二维码中填充的内容
			$createSecret = ['secret' =>$secret ,'codeurl'=>$qrCodeUrl];
			
		}else
		{
			$createSecret = google_create_secret(32);
		}
		// 您自定义的参数，随表单返回
		$box = new Box('Google 验证绑定',view($this->googleView, ['createSecret' => $createSecret]) );
		$box->removable();
		$box->collapsable();
		$box->style('info');
		return $box->render();
		
		
	}
	
	public function getSetting(Content $content)
	{
		$form = $this->settingForm();
		$form->tools(
			function (Form\Tools $tools) {
				$tools->disableList();
				$tools->disableDelete();
				$tools->disableView();
			}
		);

		return $content
			->title(trans('admin.user_setting'))
			->row(function (Row $row) use ($form) {

				$row->column(9, function (Column $column) use ($form) {
					$column->append($form->edit(Admin::user()->id));
				});

				$row->column(3, function (Column $column){
					$column->append($this->google());
				});

			});
	}
	
	
	public function googlePost(Request $request)
	{
		$onecode = (string) $request->onecode;
		if (empty($onecode) && strlen($onecode) != 6)
		{
			admin_toastr('请正确输入手机上google验证码 !','error');
			return response()->json(['message' => '请正确输入手机上google验证码 !','status'=> FALSE,]);
		}
		// google密钥，绑定的时候为生成的密钥；如果是绑定后登录，从数据库取以前绑定的密钥
		$google = $request->google;
		
		
		// 验证验证码和密钥是否相同
		if(google_check_code((string)$google,$onecode,1))
		{
			$admi_user = auth('admin')->user();
			$admi_user->google_auth = $google;
			$admi_user->save();
			admin_toastr('认证成功');
			return response()->json(['message' => '认证成功 !','status'=> TRUE,]);
		}
		else
		{
			admin_toastr('验证码错误，请输入正确的验证码 !','error');
			return response()->json(['message' => '验证码错误，请输入正确的验证码 !','status'=> FALSE,]);
		}
	}
	
	public function setGoogleAuth(Request $request)
	{
		$is_open_google_auth = $request->get('is_open_google_auth');
		
		auth('admin')->user()->is_open_google_auth = $is_open_google_auth;
		auth('admin')->user()->save();
		admin_toastr('设置成功');
		return response()->json(['message' => '设置成功 !','status'=> TRUE,]);
	}

	
	
	/**
	 * Model-form for user setting.
	 *
	 * @return Form
	 */
	protected function settingForm()
	{
		$class = config('admin.database.users_model');
		
		$form = new Form(new $class());
		
		$form->display('username', trans('admin.username'));
		$form->text('name', trans('admin.name'))->rules('required');
		$form->image('avatar', trans('admin.avatar'));
		$form->password('password', trans('admin.password'))->rules('confirmed|required');
		$form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
			->default(function ($form) {
				return $form->model()->password;
			});
		
		$form->setAction(admin_url('auth/setting'));
		
		$form->ignore(['password_confirmation']);
		
		$form->saving(function (Form $form) {
			if ($form->password && $form->model()->password != $form->password) {
				$form->password = bcrypt($form->password);
			}
		});
		
		$form->saved(function () {
			admin_toastr(trans('admin.update_succeeded'));
			
			return redirect(admin_url('auth/setting'));
		});
		
		return $form;
	}
}