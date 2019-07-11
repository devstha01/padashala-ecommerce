<div class="table table-reponsive">
    <a href="{{route('add-shopping-rate',['type'=>'standard'])}}" class="btn btn-info"><i class="fa fa-plus"></i> {{__('dashboard.Add Standard')}}</a>
    <br>
    <br>
    <table class="table table-bordered">
        <tr>
            <th>{{__('dashboard.Generation')}}</th>
            <th>{{__('dashboard.Package')}}</th>
            <th>{{__('dashboard.Percentage')}}</th>
            <th colspan="1">{{__('dashboard.Action')}}</th>
        </tr>
        @foreach($standard as $stan)
            <tr>
                <td>
                    @if($stan->generation_position == 0 )
                        {{$stan->generation_position}} / {{__('dashboard.Self')}}
                    @else
                        {{$stan->generation_position}}
                    @endif
                </td>
                <td>{{$stan->package->name}}</td>
                <td>{{$stan->percentage}}</td>
                <td><a href="{{route('edit-shopping-rate',['type'=>'standard','id'=>$stan->id])}}" class="btn blue"><i class="fa fa-edit"></i> {{__('dashboard.Edit')}}</a></td>
                {{--<td><a onclick="return confirm('Are you sure?')" href="{{route('delete-shopping-rate',['type'=>'standard','id'=>$stan->id])}}" class="btn red"><i class="fa fa-trash"></i> {{__('dashboard.Remove')}}</a></td>--}}
            </tr>
        @endforeach
    </table>
</div>