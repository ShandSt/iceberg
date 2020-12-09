<div class="left-sidebar-wrapper">
    <div class="left-sidebar">
        <div class="category-caption">
            <i class="fa fa-chevron-left" aria-hidden="true"></i> Все категории
            <span style="color: #A4A4A4; font-family: 'OpenSansRegular', Arial, Tahoma, sans-serif; font-weight: normal;">({{ \App\Models\ProductNew::count() }})</span>
        </div>
        <ul class="category-menu">
            @foreach(\App\Models\Category::orderBy('position')->whereHas('products')->withCount('products')->get() as $index => $category)
                <li class="{{ isset($currentCategory) && $currentCategory->id === $category->id ? 'curent' : '' }}">
                    <a href="{{ route('catalog', ['category' => $category->cid]) }}">
                        {{ $category->name }}&nbsp;&nbsp;
                        <span class="quantity">({{ $category->products_count }})</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <a href="{{ route('price') }}" target="_blank" class="unload-full-catalog">Выгрузить полный каталог товаров</a>
    <span class="unload-full-catalog-text">нажимая кнопку “Выгрузить полный каталог товаров” вы получите актуальный<br> каталог товаров в формате *.pdf на текущую дату</span>
</div>