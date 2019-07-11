@extends('backend.layouts.master')

@section('content')
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="container">
                    <div class="row">
                        @if($type ==='standard')
                            <h3>{{__('dashboard.Add')}} {{__('dashboard.Standard Bonus')}}</h3>
                        @elseif($type === 'auto')
                            <h3>{{__('dashboard.Add')}} {{__('dashboard.Auto Bonus')}}</h3>
                        @elseif($type ==='special')
                            <h3>{{__('dashboard.Add')}} {{__('dashboard.Special Bonus')}}</h3>
                        @endif
                        <div class="col-md-12">
                            <div class="portlet light">
                                @include('fragments.message')
                                <form action="{{route('create-shopping-rate',['type'=>$type])}}"
                                      method="post">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label>{{__('dashboard.Generation')}}</label>
                                            <input type="text" class="form-control" name="generation"
                                                   value="{{old('generation')}}">
                                        </div>
                                        @if($type ==='standard')
                                            <div class="col-md-4 form-group">
                                                <label>{{__('dashboard.Package')}} </label>
                                                <select name="package" class="form-control">
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" {{($package->id === old('package'))?'selected':''}}>{{$package->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-md-4 form-group">
                                            <label>{{__('dashboard.Percentage')}}</label>
                                            <input type="text" class="form-control" name="percentage"
                                                   value="{{old('percentage')}}">
                                        </div>
                                        <button type="submit" class="btn blue">{{__('dashboard.Save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
