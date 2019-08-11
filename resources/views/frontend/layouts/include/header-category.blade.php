<div class="header-dropdown dropdown-expanded">
    <div class="header-menu">
        <ul>
            @forelse($home_categories->where('is_highlighted',1) as $category)
                @if($loop->index <5)
                    <li class="account-dropdown">
                        <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                            {{$category->name}}</a>
                        <div class="account-dropdown-menu" style="color: black">
                            @forelse($category->getSubCategory->where('status',1) as $subCategory)
                                <a class="fa m-3"
                                   href="{{route('product-by-category',['type'=>'sub-category','slug'=>$subCategory->slug])}}">
                                    {{$subCategory->name}}</a>
                            @empty
                            @endforelse
                        </div>
                    </li>
                @endif
            @empty
            @endforelse
        </ul>
    </div>
</div>