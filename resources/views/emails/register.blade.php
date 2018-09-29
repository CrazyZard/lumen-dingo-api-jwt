<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
</head>
<body>
    <div class="mail-box">
        <h3>hello <strong>{{$name}}</strong>：</h3>
        <p>感谢您来到<strong>eth_p2p.com</strong>，点击以下链接激活您的账户（有效期：<strong class="active-time">10分钟</strong>）：</p>
        <p><a href="{{$url}}" class="active-url">{{$url}}</a></p>
        <p>谢谢！</p>

    </div>
    <style type="text/css">
        .mail-box {

        }
        .active-time {
            color: red;
        }
        .active-url {
            text-decoration: underline;
        }
        .intro-box {
            border: 1px solid #666;
            padding: 20px;
            margin: 10px 0;
        }

    </style>
</body>
</html>