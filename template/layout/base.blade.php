<!doctype html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title")</title>
    @yield("header")
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet/less" type="text/css" href="/source/main.less" />
</head>
<body>
@include("component.banner")
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-3">
            @include("component.navbar")
        </div>
        <div class="col-lg-9 col-md-9">
            @yield("bread")
            @yield("container")
        </div>
    </div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/less@4" ></script>
@yield("footer")
</body>
</html>