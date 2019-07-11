<div class="table table-responsive">
    <table class="table table-bordered text-center">
        <tr>
            <td style="font-weight: bold">{{__('dashboard.Generation')}}</td>
            <td style="font-weight: bold" colspan="3">{{__('dashboard.Standard')}}</td>
            <td style="font-weight: bold">{{__('dashboard.Auto')}}</td>
            <td style="font-weight: bold">{{__('dashboard.Special')}}</td>
        </tr>
        <tr>
            <td></td>
            <td>{{__('dashboard.Gold')}}</td>
            <td>{{__('dashboard.Platinum')}}</td>
            <td>{{__('dashboard.Diamond')}}</td>
            <td></td>
            <td></td>
        </tr>
        @for($i=0;$i<13;$i++)
            <tr>
                <td>
                    {{$i}}
                    @if($i===0)
                        /{{__('dashboard.Self')}}
                    @endif
                </td>
                <td>
                    <span class="update-rate" data-generation="{{$i}}" data-type="standard"
                          data-package="1">{{$standard->where('generation_position',$i)->where('package_id',1)->first()->percentage??0}}</span>%
                </td>
                <td>
                    <span class="update-rate" data-generation="{{$i}}" data-type="standard"
                          data-package="2">{{$standard->where('generation_position',$i)->where('package_id',2)->first()->percentage??0}}</span>
                    %
                </td>
                <td>
                    <span class="update-rate" data-generation="{{$i}}" data-type="standard"
                          data-package="3">{{$standard->where('generation_position',$i)->where('package_id',3)->first()->percentage??0}}</span>
                    %
                </td>
                <td>
                    <span class="update-rate" data-generation="{{$i}}"
                          data-type="auto">{{$auto->where('generation_position',$i)->first()->percentage??0}}</span>
                    %
                </td>
                <td>
                    <span class="update-rate" data-generation="{{$i}}"
                          data-type="special">{{$special->where('generation_position',$i)->first()->percentage??0}}</span>
                    %
                </td>
            </tr>
        @endfor
        <tr>
            <td colspan="6">
                <br>
                <span class="alert fade" id="single-bonus-rate-message"></span>

                <button class="btn blue" id="single-bonus-rate"><i class="fa fa-edit"> </i> Edit</button>
                <button class="btn blue fade" id="save-single-bonus-rate"><i class="fa fa-save"> </i> Save</button>
            </td>
        </tr>
    </table>
</div>