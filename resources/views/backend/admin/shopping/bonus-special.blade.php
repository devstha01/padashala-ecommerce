<div class="table table-reponsive">
    <a href="{{route('add-shopping-rate',['type'=>'special'])}}" class="btn btn-info"><i class="fa fa-plus"></i> {{__('dashboard.Add')}}</a>
    <br>
    <br>
    <table class="table table-bordered">
        <tr>
            <th>{{__('dashboard.Generation')}}</th>
            <th>{{__('dashboard.Percentage')}}</th>
            <th colspan="1">{{__('dashboard.Action')}}</th>
        </tr>
        @foreach($special as $spec)
            <tr>
                <td>
                    @if($spec->generation_position == 0 )
                        {{$spec->generation_position}} / {{__('dashboard.Self')}}
                    @else
                        {{$spec->generation_position}}
                    @endif
                </td>
                <td>{{$spec->percentage}}</td>
                <td><a href="{{route('edit-shopping-rate',['type'=>'special','id'=>$spec->id])}}" class="btn blue"><i class="fa fa-edit"></i> {{__('dashboard.Edit')}}</a></td>
                {{--<td><a onclick="return confirm('Are you sure?')" href="{{route('delete-shopping-rate',['type'=>'special','id'=>$spec->id])}}" class="btn red"><i class="fa fa-trash"></i> {{__('dashboard.Remove')}}</a></td>--}}
            </tr>
        @endforeach
    </table>
</div>