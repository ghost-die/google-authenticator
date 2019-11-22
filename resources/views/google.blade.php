<div>
	<div class="">
		<p class="">两步验证</p>
		<p>请下载 Google 的两步验证器。</p>
		<p><i class="fa fa-android"></i><a id="android" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">&nbsp;Android</a></p>
		<p><i class="fa fa-apple"></i><a id="ios" href="https://itunes.apple.com/cn/app/google-authenticator/id388497605?mt=8">&nbsp;iOS</a></p>

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
					{!! QrCode::encoding('UTF-8')->size(200)->margin(1)->errorCorrection('H')->generate($createSecret["codeurl"]); !!}
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
				url:"{{ route('admin.GoogleAuthenticator')}}",
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

		$('#android').popover({
			trigger : 'hover',
			html:true,
			content:"<img height='100' width='100' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQAAAAB0CZXLAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAd2KE6QAAAAHdElNRQfjCxUOBxONcq8jAAACnElEQVRo3tWZPY7rMAyEKbhI6SPkKL6ZldzMR/ERXKowzMeZkTa722z5TEEIAuhrCP4NafM/jt0BaBantOfb99Xid7N5O82meMJZkgDPsCVeawP2wtMW95w9GD/SAC9YtxdipS0wzZbTlumwKRfQrPoevqlhIF7NwsyEQGm2PvbqHq+w0ZMBCDmkD7AVPloQab9i8u7ASP9+Aej+rg93BlRw5SnCJxIHvz9q9c2BcNaIOiMTDjrnzaMmm3Vv3h9A4XpeFtcK7Y0ibEycTACts4dfvO8OIPH9K3EyACue0NkdJivY0N9PdPkkANo6Ej/aukIuylcPOfzxJIBHsK2It11e8yMizdjf2VBSAE11WD1xXx9wkBh2xiwAPMWs2U1njoQJxSiXeRIgxJXLzPBX1RMbChJnzgJ0rVgoFwuscwqtTdI3B8CnghnKqN43+qunf+84CQCca2itq1dgMKaQywGgcIVEgVCJCtaY+72zm5z1/4FvOlDi3Ob+2iV6CoD9mrGEG2bKEfE6GkoKAJ6KSvuCyzDxyUwWKCmQHIDDzF1qil7rOsq9K5AMAFp2pacuzd3Ia3ZtLkNyANinwV/c50T7W+SpiVJKY1QCgM2isoK9WAJ8TNzTYXkAf9NS5g4VCAE/PoN5AiDkB5tdTEmyNLKGKbNxPbjkABzWFU3faIvGJS0b93BWCkCrA2MdLsNZ5/BXDoDNXdsDpybkAkTp/5Eodwf6Vk3jHjQtPGVdn0xHEqBvOLE6CBtrSBTOF58ilgN4OnsihQq+X2BJi3EPZp6WBujfLxrrGJe02OEwlTSxpgG898Q6Jlbj3G3JgL6t9abOblqA/Phkdm+AIacNJ0ZXfoLZhsuyAEp/rc3DTKlEU9Zo1ZMA8D9OCuAfOvBOGWO+UU0AAAAldEVYdGRhdGU6Y3JlYXRlADIwMTktMTEtMjFUMjE6MDc6MTktMDc6MDDQWz//AAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE5LTExLTIxVDIxOjA3OjE5LTA3OjAwoQaHQwAAAABJRU5ErkJggg=='>",

		})
		$('#ios').popover({
			trigger : 'hover',
			html:true,
			content:"<img height='100' width='100' src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQAAAAB0CZXLAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAd2KE6QAAAAHdElNRQfjCxUOCQp3miptAAACe0lEQVRo3t2ZMZKDMAxFzVC45AgchZsFcjMfhSNQUjDW/v8F2U22SIvlmZAEXgpZsr6kJPuyUhPAkbgGs35P09O2NBsvx7DqwRwFyDB2H9aZXx+JH/I24d7KS2kGWFNHAGam1Busw08WfJhgfURgtIp9ALRpQxoEbIF1ZcazZAzOIxygoEUqYdD227jwzvNfVN8c8AQib5YT2GTme4ZpHDizPgDzE6edqcycb3rROHAlUsTqiE/YB3wrM7Znqni1AvCK+8b0aAfOIEMYz8YiXYgDuHZjH2A+AawK35YPcb81kJEQ80ZPrhQ1mEp7mTCVOcMAZyL1oAWFh5Mxw3xk2lsDPGMZCcT25KvH0UtImKVm86o4AEDzO6leXhW9Cz2qWgVOphzEALA6hiiVgPkTr0FyUOpL9ZoA4MhRekZK5bHqyW4PBrA3gwC46vW7uxRBSzloAjhwP3nNb6VjFWI6eqYIVYaJAFDsOjc/saVmrTJQ9dZUJQdtALCOMUktKyqPr4Q5SdKDAPJm9tamKNV4styu9NkIQHvgSI4DdN/t9XLRK7EAADtPiTTjdeabJleHAA/aEADOpv1qNzfEEyn7bmXaFgAGLQstjT7qOWZUeXz+LgigUQ+1mz0O5eDQXA6H1MWwEYCOtFUyJl1gXHImPF2dWgxAFRUSC/trNqRH3tJrNQJ4oaUWZrSF9xSKII31iAUBfv+eQE55qrf2DcH2/B0/tg6cfbfPRpL87j2ONQX4xFtBywmPsa1+0FhvYWIBC9Was2L2bRQ8XPa3yX8TAOsN7zdNE286+c3MxgEPWvOiRBPvE/iYy90a8ATiFchyeVN/T9RAgH1ZMYAfXZaYCVpMtGYAAAAldEVYdGRhdGU6Y3JlYXRlADIwMTktMTEtMjFUMjE6MDk6MTAtMDc6MDBbCkqfAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE5LTExLTIxVDIxOjA5OjEwLTA3OjAwKlfyIwAAAABJRU5ErkJggg=='>",

		})

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
