<!DOCTYPE html>
<html>
<head>
    <meta name="google-site-verification" content="GBAfBgjlshBVThGtK9Ju4DfimkrEzZ3s4sU6TRhmnOY" />
    <meta content="width=device-width,initial-scale=1" name=viewport>
    <title>TromBoy Food Delivery | Online Food Order</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <?php echo $__env->yieldContent('style'); ?>
</head>
<body>
    <?php echo $__env->make('partials.nav_main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('partials.notify', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="container" style="min-height:700px;">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script src="/js/jquery-3.1.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <?php echo $__env->yieldContent('script'); ?>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/58a70ee627e1fd0aacb543c9/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
</body>
</html>