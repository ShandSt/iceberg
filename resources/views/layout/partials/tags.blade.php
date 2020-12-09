<div class="row">
    <div class="col-lg-12">
        <div class="categoty-wrapper mob">
            <div class="categoy-box-slider">
                @foreach(App\Models\Tag::notEmpty()->get() as $tag)
                    <a href="{{ route('catalog', ['tag' => $tag->id]) }}">
                        <div class="categoy-box {{ (isset($currentTag) ? $currentTag->id : null) == $tag->id ? 'curent' : '' }}">
                            {{ $tag->name }}
                            @if($tag->hasImage())
                                <img src="{{ asset($tag->image()) }}" alt="{{ $tag->name }}" class="img-responsive">
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>