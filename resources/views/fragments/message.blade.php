@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
        <h4><i class="icon fa fa-check"></i> {{session('success')}}</h4>
    </div>
@elseif(session('fail'))
    <div class="alert alert-danger alert-dismissible">
        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
        <h4><i class="icon fa fa-ban"></i> {{session('fail')}}</h4>
    </div>
@elseif(session('info'))
    <div class="alert alert-info alert-dismissible">
        {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
        <h4><i class="icon fa fa-info"></i> {{session('info')}}</h4>
    </div>
@endif