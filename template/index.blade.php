@extends("layout.base")
@section("title")
    {{\Watish\StaticPageBuilder\Util\ENV::get("APP_NAME")}}
@endsection
@section("container")
    <div class="card-list">
        @foreach(\Watish\StaticPageBuilder\Content\ContentCollector::allFiles('/') as $contentDrawer)
            <div class="card-item">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{$contentDrawer->getName()}}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{date("Y年m月d日",$contentDrawer->getLastModified())}}</h6>
                        <p class="card-text">{!! $contentDrawer->getPureText(30) !!}...</p>
                        <a href="{{$contentDrawer->linker("template.content")->getLink()}}" class="card-link">阅读</a>
                    </div>
                    <div class="card-footer" style="text-align: right">
                        {{ $contentDrawer->fromDir() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
