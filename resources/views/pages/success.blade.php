<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="D257iKFeGr4cxj-B7utmfYSKm_Jp1LJTb216Jo_hm4E" />
    <meta name='yandex-verification' content='4533c1da83254fda' />
    <base href=""/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Янтарный Айсберг производитель воды высшей категории качества</title>
    <base href="" /><meta name="keywords" content="Янтарный Айсберг, айсберг, вода, качество, Калининград, кулер для воды, доставка, на дом,  в офис, Зеленоградские источники, вода питьевая, питьевая, бутилированная, бутылированная, ассортимент, качество, заказ, water" />
    <meta name="description" content="Доставка питьевой воды в Калининграде и области. Вода высшей категории качества" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/datepicker3.css" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/jquery.kladr.min.css" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/lightSlider.css" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/style.css" />


    <!-- modal window 1, window 2, window 3 -->
    <!-- Latest compiled and minified CSS https://silviomoreto.github.io/bootstrap-select/ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- modal window 1, window 2, window 3 -->
    <!-- stylesheet -->
    <link rel="stylesheet" href="{{URL::to('/')}}/css/iceberg.css">


    <!-- modal window 1, window 2, window 3 -->


    <link rel="icon" href="media/images/favicon.ico" type="image/vnd.microsoft.icon" /><link rel="shortcut icon" href="media/images/favicon.ico" type="image/vnd.microsoft.icon" />
    <!--[if lt IE 9]>
    <script src="../../assets/js/html5shiv.js"></script>
    <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->

    <script>
        var disabledDates = [], halfDates = [], workDates = [];
        try {
            disabledDates = ['01.01', '02.01', '04.01', '05.01', '07.01', '31.12'];
        } catch (error){}
        try {
            halfDates = ['06.01', '03.01', '08.01'];
        } catch (error){}
        try {
            workDates = ['30.12'];
        } catch (error){}
    </script>

</head>

<body>
<div id="wrapper">

    <div id="success" class="modal-dialog modal-lg modal-wrapper">
        <div class="modal-content">

            <div class="modal-body">
                <div class="success-content">
                    <img class="success-content__check" src="/img/check.png" alt="success">
                    <h4 class="success-content__info">Ваш заказ<br>успешно оформлен</h4>
                    <h5 class="success-content__number">Номер заказа: <span id="id"></span></h5>
                    <h6 class="success-content__delivery">Доставка: <span id="delivery"></span></h6>
                    <a href="http://iceberg-aqua.ru" title="" id="get-back" class="btn btn_submit success-content__back">Перейти на главную страницу</a>
                </div>
                <div class="app-links">
                    <div class="app-links__description">
                        Теперь для заказа воды Вы можете скачать мобильное приложение
                    </div>
                    <a href="https://itunes.apple.com/ru/app/айсберг/id1288250696?mt=8&ign-mpt=uo%3D4"><img class="app-store" src="/img/appStore.png" alt="AppStore"></a>
                    <a href="https://play.google.com/store/apps/details?id=rewarded.mobiap.com.icebegr"><img class="play-market" src="/img/googlePlay.png" alt="Play Market"></a>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter21513511 = new Ya.Metrika({id:21513511, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="http://mc.yandex.ru/watch/21513511" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-34064606-1', 'auto');
    ga('send', 'pageview');
</script>
<script>
    let id = document.getElementById('id');
    id.innerText = localStorage.getItem('id');
    let delivery = document.getElementById('delivery');
    delivery.innerText = localStorage.getItem('delivery');
</script>

</body>
</html>
