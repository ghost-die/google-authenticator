<div>
	<div class="">
		<p class="">两步验证</p>
		<p>请下载 Google 的两步验证器。</p>
		<p><i class="fa fa-android"></i><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">&nbsp;Android</a></p>
		<p><i class="fa fa-apple"></i><a href="https://itunes.apple.com/cn/app/google-authenticator/id388497605?mt=8">&nbsp;iOS</a></p>

		<p>如果遇到问题，请参考：<a href="https://phpartisan.cn/specials/5" target="_blank">Google Authenticator帮助文档</a></p>

		<p>在没有测试完成绑定成功之前请不要启用。</p>
		<p>当前设置：@if(auth('admin')->user()->is_open_google_auth) <code> 要求验证 </code> @else <code> 不要求验证 </code>  @endif</p>
		<p>当前服务器时间：<span class="text-red" id="txt"></span></p>
		<div class="form-group form-group-label control-highlight-custom dropdown control-highlight">
			<label class="floating-label" for="ga-enable">验证设置</label>
			<button type="button" id="ga-enable" class="form-control maxwidth-edit" data-toggle="dropdown" value="{{auth('admin')->user()->is_open_google_auth}}">
				@if(auth('admin')->user()->is_open_google_auth)  要求验证 @else 不要求验证  @endif
			</button>
			<ul class="dropdown-menu text-center"  style="width: 100%;border-radius: unset;box-shadow: 0 6px 12px rgba(0,0,0,.175);">
				<li><a class="no_auth" >不要求</a> </li>
				<li><a class="yes_auth" >要求验证</a></li>
			</ul>
		</div>
		<div class="form-group form-group-label">
			<div class="text-center">
				<div>
					{!! QrCode::encoding('UTF-8')->size(200)->margin(1)->generate($createSecret["codeurl"]); !!}
				</div>
				<h4 class="">
					密钥：<span class="text-red">{{ $createSecret["secret"] }}</span>
				</h4>
			</div>
		</div>
		<div class="form-group form-group-label">
			<label class="floating-label" for="code">测试一下</label>
            <input type="hidden" name="google" value="{{ $createSecret['secret'] }}" />
            <input name="onecode" class="form-control"  type="text" placeholder="请输入扫描后手机显示的6位验证码" value="{{ old('onecode') }}" />
{{--			<input type="text" id="code" placeholder="输入验证器生成的数字来测试" class="form-control maxwidth-edit">--}}
		</div>
	</div>
	<div class="form-group text-center">
		<div class="">
			<button class="btn btn-default test">测试</button>
			<button class="btn btn-primary success">设置</button>
		</div>
	</div>
</div>


<script>

	$(document).ready(function(){

		$('.no_auth').on('click',function (e) {
			$('#ga-enable').val(0);
			$('#ga-enable').html($(this).html());
		});

		$('.yes_auth').on('click',function (e) {
			$('#ga-enable').val(1);
			$('#ga-enable').html($(this).html());

		});

        $('.test').on('click',function (e) {

			$.ajax({
				url:"{{ empty(config('google.authenticatorurl')) ? route('admin.GoogleAuthenticator') : config('google.authenticatorurl') }}",
				type:'post',
				data:{_token:LA.token,google:$("input[name='google']").val(),onecode:$("input[name='onecode']").val()},
				success:function (obj) {
					console.log(obj);
					$.pjax.reload('#pjax-container');
				}
			});

        });
        $('.success').on('click',function (e) {

			$.ajax({
				url:"{{ route('admin.setUserGoogleAuth') }}",
				type:'post',
				data:{_token:LA.token,is_open_google_auth:$('#ga-enable').val()},
				success:function (obj) {
					console.log(obj);
					$.pjax.reload('#pjax-container');
				}
			});
        });

	});

	startTime();
	function startTime()
	{
		//获取当前系统日期
		var myDate = new Date();
		var y=myDate.getFullYear(); //获取当前年份(2位)
		var m=myDate.getMonth()+1; //获取当前月份(0-11,0代表1月)
		var d=myDate.getDate(); //获取当前日(1-31)
		var h=myDate.getHours(); //获取当前小时数(0-23)
		var mi=myDate.getMinutes(); //获取当前分钟数(0-59)
		var s=myDate.getSeconds(); //获取当前秒数(0-59)
		var hmiao=myDate.getMilliseconds(); //获取当前毫秒数(0-999)
		//s设置层txt的内容
		document.getElementById('txt').innerHTML=y+"-"+m+"-"+d+" "+h+":"+mi+":"+s;
		//过500毫秒再调用一次
		t=setTimeout('startTime()',500)
		//小于10，加0
		function checkTime(i)
		{
			if(i<10)
			{i="0"+i}
			return i
		}
	}



</script>
