

@if(Session::has('message'))
    <div class="content_top" >
        <div  class="alert alert-success">
            {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}
        {{ session('message') }} 
        </div>
    </div>
@endif

@if(Session::has('successMemberRegister'))
    <div class="content_top" >
        <div  class="alert alert-success">
            {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}
            {{ session('successMemberRegister') }}
        </div>
    </div>
@endif


@if(Session::has('error'))
    <div class="content_top" >
        <div  class="alert alert-danger">
            {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}
        {{ session('error') }} 
        </div>
    </div>
@endif
