@php $drawer = \Watish\StaticPageBuilder\Reactive\ContentReactive::getDrawer(); @endphp
@extends("layout.base")
@section("title")
    {{ $drawer->getName() }} - {{ \Watish\StaticPageBuilder\Util\ENV::get("APP_NAME") }}
@endsection
@section("header")
    <link rel="stylesheet" href="/source/prism/prism.css">
@endsection
@section('bread')
    <nav aria-label="breadcrumb" style="margin-top: 15px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">首页</a></li>
            <li class="breadcrumb-item"><a href="{{ $drawer->fromDirDrawer()->linker("template.dir")->getLink() }}">
                    {{ $drawer->fromDirDrawer()->getDirName() }}
                </a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $drawer->getName() }}
            </li>
        </ol>
    </nav>
@endsection
@section("container")
    <div class="content" style="display: flex;justify-content: center;flex-direction: column">
        <div class="section-header">
            <div class="section-bar"></div>
            <div class="section-text">
                {{ $drawer->getName() ?? "Null" }}
            </div>
        </div>
        <div class="section-sec-header">
            撰写于 {{ date("Y年m月d日",$drawer->getLastModified()) ?? "未知" }}
        </div>
        {!! $drawer->getHtml() !!}
    </div>
@endsection
@section("footer")
    <script type="text/javascript" src="/source/prism/prism.js"></script>
@endsection
