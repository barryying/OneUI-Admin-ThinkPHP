{__NOLAYOUT__}<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>消息提示 - Quick Delivery </title>
    <meta name="author" content="Quick Delivery">
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="__STATIC__/img/favicons/favicon.png">
    <!-- END Icons -->
    <!-- OneUI CSS framework -->
    <link rel="stylesheet" id="css-main" href="__STATIC__/css/oneui.css">
    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="__STATIC__/css/themes/flat.min.css"> -->
    <!-- END Stylesheets -->
</head>
<body>
<!-- Error Content -->
<div class="content bg-white text-center pulldown overflow-hidden">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            {php}$errCode = $code == 1 ? 'success' : ($code == 0 ? 'error' : 'info');{/php}
            <!-- Error Titles -->
            <div class="font-s128 font-w300 animated bounceInDown" style="padding-bottom: 50px;">
                <img src="__STATIC__/img/{$errCode}.svg" alt="" width="150" />
            </div>
            <h2 class="h2 font-w300 push-50 animated fadeInUp"> {$msg}</h2>
            <h2 class="h3 font-w300 push-50 animated fadeInUp"> 此页面将在<span id="wait">{$wait} </span>秒后跳转 </h2>
            <!-- END Error Titles -->
            <p class="clearfix">
                <a href="javascript:history.go(-1);" class="btn btn-minw btn-rounded btn-primary">返回上层</a>
                <a href="{$url}" class="btn btn-minw btn-rounded btn-success">立即跳转</a>
            </p>
        </div>
    </div>
</div>
<!-- END Error Content -->

<!-- Error Footer -->
<div class="content pulldown text-muted text-center">
    <p>Powered by <a href="#">Quick Delivery</a>.  v{$Think.config.website.version}</p>
</div>
<!-- END Error Footer -->

<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<script src="__STATIC__/js/core/jquery.min.js"></script>
<script src="__STATIC__/js/core/bootstrap.min.js"></script>
<script src="__STATIC__/js/core/jquery.slimscroll.min.js"></script>
<script src="__STATIC__/js/core/jquery.scrollLock.min.js"></script>
<script src="__STATIC__/js/core/jquery.appear.min.js"></script>
<script src="__STATIC__/js/core/jquery.countTo.min.js"></script>
<script src="__STATIC__/js/core/jquery.placeholder.min.js"></script>
<script src="__STATIC__/js/core/js.cookie.min.js"></script>
<script src="__STATIC__/js/app.js"></script>

<script type="text/javascript">
    (function () {
        var wait = document.getElementById('wait');
        var interval = setInterval(function () {
            var time = --wait.innerHTML;
            if (time <= 0) {
                location.href = "{$url}";
                clearInterval(interval);
            }
        }, 1000);
    })();
</script>
</body>
</html>