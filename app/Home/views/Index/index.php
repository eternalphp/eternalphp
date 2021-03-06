<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>港航-红帆</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone = no" />
    <style>
        a:active, a:hover{text-decoration: none;}
        a{text-decoration: none;}
        a:focus, a:hover{text-decoration: none;}
        body{background: #fff;color: #474f64;}
        i{display: inline-block;}
        li{list-style: none;margin: 0;padding: 0;}
        ul{margin: 0;padding: 0;}
        *{margin: 0;padding: 0;font-weight: normal;}
        h1,h2,h3,h4,h5,h6{font-weight: normal;}
        body{background: #fff;overflow-x: hidden;color: #333;}
        .container-fluid{padding-left: 0.65rem;padding-right: 1.2rem;}
        
        html{font-size: 20px;}
        @media only screen and (min-width: 320px){html{font-size: 19px !important;}}
        @media only screen and (min-width: 375px){html{font-size: 20px !important;}}
        @media only screen and (min-width: 400px){html{font-size: 21.33333333px !important;}}
        @media only screen and (min-width: 414px){html{font-size: 22.08px !important;}}
        @media only screen and (min-width: 480px){html{font-size: 25.6px !important;}}
        html[data-rem="320"]{font-size: 20px;}
        @media only screen and (min-width: 375px){html[data-rem="320"]{font-size: 23.4375px !important;}}
        @media only screen and (min-width: 400px){html[data-rem="320"]{font-size: 25px !important;}}
        @media only screen and (min-width: 414px){html[data-rem="320"]{font-size: 25.875px !important;}}
        @media only screen and (min-width: 480px){html[data-rem="320"]{font-size: 30px !important;}}
        .icon-right-arrow{background: url("/themes/index/images/icon-left-arrow.png") no-repeat;width: 0.45rem;height: 0.85rem;background-size: 0.45rem 0.85rem;padding-right: 1.5rem;}
        header a{font-size: 0.9rem;color: #fff;display: flex;align-items: center;padding-left: 0.65rem;padding-top: 0.8rem;}
        .img-user{    flex-shrink: 0;}
        .img-top{width: 100%;top: 0;position: absolute;z-index: -1;height: 100%;}
        .word-title{width: 100%;display: block;margin: 0 auto;margin-top: 1rem;}
        .chat-user{position: relative;margin: 0.3rem auto 0 auto;}
        .img-chat-1{width: 100%;vertical-align: middle;}
        .right-con{font-size: 0.7rem;color: #333;text-align: justify;letter-spacing: 2px;}
        .right-con span{color: #de1617;font-size: 0.75rem;}
        .right-con img{width: 1.2rem;position: relative;top: 0.1rem;margin: 0 0.2rem;}
        .chat-con{display: flex;align-items: center;position: absolute;left: 0;right: 0;padding: 0 1.6rem 0 1.2rem;height: 100%;}
        .img-user{width: 1.75rem;padding-right: 0.5rem;position: relative;top: -0.6rem;}
        .chat-con2{position: absolute;padding: 0 1.6rem 0 1.2rem;left: 0;right: 0;height: 100%;}
        .chat-con2 span{font-size: 0.7rem;color: #ca191c;}
        .chat-user2{position: relative;margin: 0.6rem auto 0 auto;}
        .row-line{display: flex;position: relative;padding-top: 0.7rem;padding-bottom: 0.4rem;align-items: center;}
        .row-line:after{position: absolute;
            bottom: 0;
            left: 2.4rem;
            height: 1px;
            right: 3rem;
            content: '';
            -webkit-transform: scaleY(.5);
            transform: scaleY(.5);
            background-color: #e5e5e5;width: 84%;}
        .row-line .img-user{top: 0.3rem;flex-shrink: 0;}
        .right-con2 span img{width: 0.75rem;position: relative;top: 0.2rem;left: 0.3rem;}
        .chat-con2 ul li img{width: 1.15rem;}
        .chat-con2 ul li a{font-size: 0.7rem;color: #333;display: flex;align-items: center;}
        .chat-con2 ul{width: 73%;margin: 0 auto;display: flex;align-content: stretch;flex-direction: column;justify-content: space-around;height: 72%;margin-top: 0.6rem;}

    </style>
</head>
<body>
<main>
    <img class="img-top" src="/themes/index/images/img-bac.jpg" />
    <img class="word-title" src="/themes/index/images/word-title.png" />
    <div class="container-fluid">
        <div class="chat-user">
            <div class="chat-con">
                <img class="img-user" src="/themes/index/images/img-user.png" />
                <div class="right-con">
                   <span><?=session("name")?>同志</span><img src="/themes/index/images/icon-call.png" /> 您好，欢迎来到求真的世界，务实的港航，请问我能为您提供什么服务？
                </div>
            </div>
            <img class="img-chat-1" src="/themes/index/images/img-chat-1.png" />
        </div>



        <div class="chat-user2">
            <div class="chat-con2">
                <div class="row-line">
                    <img class="img-user" src="/themes/index/images/img-user.png" />
                    <div class="right-con2">
                        <span>请点击选择需要的服务<img src="/themes/index/images/icon-sz.png" /></span>
                    </div>
                </div>
                <ul>
                    <li><a href="/news/"><img src="/themes/index/images/icon-dz.png" />_我要学习</a></li>
                    <!--<li><a href="#"><img src="/themes/index/images/icon-dz.png" />_我要美化自己的家园</a></li> -->
                     <!--<li><a href="#"><img src="/themes/index/images/icon-dz.png" />_我要访友</a></li>-->
                     <!--<li><a href="#"><img src="/themes/index/images/icon-dz.png" />_我要开会</a></li>-->
                    <li><a href="/discuss"><img src="/themes/index/images/icon-dz.png" />_我要参与讨论</a></li>
                    <li><a href="/work/"><img src="/themes/index/images/icon-dz.png" />_我要进行党务工作</a></li>
                    <li><a href="/suggest/"><img src="/themes/index/images/icon-dz.png" />_我有意见和建议</a></li>
                </ul>
            </div>
            <img class="img-chat-1" src="/themes/index/images/img-chat-2.png" />
        </div>

    </div>



</main>
</body>
</html>