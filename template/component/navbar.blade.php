<div class="navbar">
    <div class="item">
        <a href="/">首页</a>
    </div>
    @foreach(\Watish\StaticPageBuilder\Content\ContentCollector::listDir("/") as $dir)
        <div class="item">
            <a href="{{$dir->linker("template.dir")->getLink()}}">{{$dir->getDirName()}}</a>
        </div>
    @endforeach
</div>