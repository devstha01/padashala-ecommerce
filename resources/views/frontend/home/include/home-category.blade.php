<div class="col-lg-3 order-lg-first">
    <div class="side-custom-menu" id="withDropDown">
        <h2>{{__('front.Categories')}}</h2>
        <div class="side-menu-body" style="position:relative;padding: 0;">
            <ul class="levelZero">

                @foreach($all_categories as $h1=>$category)
                    @if($h1<8)
                        @if(count($category->getSubCategory->where('status',1)) !==0)
                            <li class="arrow-on-hover">
                                <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                                    {{$category->name}}
                                </a>
                                <div class="levelOne">
                                    <ul>
                                        <?php $homecount = 0; ?>
                                        @foreach($category->getSubCategory->where('status',1)  as  $h2=>$homeSubCat)
                                            @if($homecount <8)
                                                <?php $homecount++; ?>
                                                <li>
                                                    <a href="{{route('product-by-category',['type'=>'sub-category','slug'=>$homeSubCat->slug])}}">
                                                        {{$homeSubCat->name}}
                                                    </a>
                                                </li>
                                            @else
                                                @if($loop->last)
                                                    <br>
                                                    <a class="text-right text-primary"
                                                       href="{{route('all-categories')}}">{{__('front.view all')}}</a>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @else
                            <li class="">
                                <a href="{{route('product-by-category',['type'=>'category','slug'=>$category->slug])}}">
                                    {{$category->name}}
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach

            </ul>
            <br>
            <a href="{{route('all-categories')}}"
               style="width: 100%;line-height:0px;height:21px;position:absolute;bottom:0"
               class="m-auto btn btn-block btn-primary btn-sm">
                {{__('front.All Categories')}} </a>
        </div><!-- End .side-menu-body -->
    </div><!-- End .side-custom-menu -->

</div><!-- End .col-lg-3 -->