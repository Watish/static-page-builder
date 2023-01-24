@extends('layout.base')
@php
    $drawerList = \Watish\StaticPageBuilder\Reactive\ContentDirReactive::getDrawerList();
    $dirDrawer = \Watish\StaticPageBuilder\Reactive\ContentDirReactive::getDirDrawer();
@endphp
@section('title')
    {{ $dirDrawer->getDirName() }} - {{ \Watish\StaticPageBuilder\Util\ENV::get("APP_NAME") }}
@endsection
@section('bread')
    <nav aria-label="breadcrumb" style="margin-top: 15px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $dirDrawer->getDirName() }}</li>
        </ol>
    </nav>
@endsection
@section('container')
    @foreach($dirDrawer->sortByDate("Y年m月") as $date => $drawerDateList)
        <div class="sort-block">
            <div class="sort-bar"></div>
            <div class="sort-text">{{ $date }}</div>
        </div>
        <div class="card-list">
            @foreach($drawerDateList as $drawer)

                <div class="card-item">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $drawer->getName() }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ date("Y年m月d日",$drawer->getLastModified()) }}</h6>
                            <p class="card-text">{!! $drawer->getPureText(30) !!}...</p>
                            <a href="{{$drawer->linker("template.content")->getLink()}}" class="card-link">阅读全文</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endsection