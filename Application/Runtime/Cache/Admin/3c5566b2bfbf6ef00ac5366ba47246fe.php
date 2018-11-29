<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>欢迎您登录影视管理系统</title>
    <link rel="stylesheet" href="/Public/Admin/css/pintuer.css">
    <link rel="stylesheet" href="/Public/Admin/css/admin.css">
    <script src="/Public/Admin/js/jquery.js"></script>
    <script src="/Public/Admin/js/pintuer.js"></script>
    <script src="/Public/Admin/js/respond.js"></script>
    <script src="/Public/Admin/js/admin.js"></script>
</head>

<body>
<div class="container">
    <div class="line">
        <div class="xs6 xm4 xs3-move xm4-move">
            <br /><br />
            <div class="media media-y">
                <a href="#" target="_blank"><img src="/Public/Admin/images/logo.png" class="radius" alt="后台管理系统" /></a>
            </div>
            <br /><br />
            <form action="<?php echo U('Public/login');?>" method="post">
            <div class="panel">
                <div class="panel-head"><strong>欢迎您登录影视管理系统</strong></div>
                <div class="panel-body" style="padding:30px;">
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="text" class="input" name="username" placeholder="登录账号" data-validate="required:请填写账号,length#>=5:账号长度不符合要求" />
                            <span class="icon icon-user"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="password" class="input" name="password" placeholder="登录密码" data-validate="required:请填写密码,length#>=5:密码长度不符合要求" />
                            <span class="icon icon-key"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field">
                            <input type="text" class="input" name="passcode" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
                            <img src="<?php echo U('Public/verify');?>" width="140" height="32" class="passcode" />
                        </div>
                    </div>
                </div>
                <div class="panel-foot text-center"><button class="button button-block bg-main text-big">立即登录后台</button></div>
            </div>
            </form>
        </div>
    </div>
</div>
	<script type="text/javascript">

    	$("form").submit(function(){
    		var self = $(this);
			self.find(".alert").remove();
    		$.post(self.attr("action"), self.serialize(), success, "json");
    		return false;

    		function success(data){
    			if(data.status){
    				window.location.href = data.url;
    			} else {
    				self.find(".panel-body").append('<div class="alert alert-red"><strong>注意：</strong>'+data.info+'</div>');
    				//刷新验证码
    				$(".passcode").click();
    			}
    		}
    	});
		$(function(){
			var verifyimg = $(".passcode").attr("src");
			 $(".passcode").click(function(){
					if( verifyimg.indexOf('?')>0){
						$(".passcode").attr("src", verifyimg+'&random='+Math.random());
					}else{
						$(".passcode").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
					}
			  });
		  });
	</script>
</body>
</html>