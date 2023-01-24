<div class="navbar">
    <div class="list-group" style="width: 100%">
        {{--        <div class="item">--}}
        {{--            <a href="/">首页</a>--}}
        {{--        </div>--}}
        <a href="/" class="list-group-item list-group-item-action">
            首页
        </a>
        @foreach(\Watish\StaticPageBuilder\Content\ContentCollector::listDir("/") as $dir)
            {{--        <div class="item">--}}
            {{--            <a href="{{$dir->linker("template.dir")->getLink()}}">{{$dir->getDirName()}}</a>--}}
            {{--        </div>--}}
            <a href="{{$dir->linker("template.dir")->getLink()}}" class="list-group-item list-group-item-action">
                {{$dir->getDirName()}}
            </a>
        @endforeach
    </div>

</div>