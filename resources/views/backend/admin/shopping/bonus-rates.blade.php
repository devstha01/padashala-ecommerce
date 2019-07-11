<div class="table table-reponsive">
    <table class="table table-bordered text-center">
        <tr>
            <td colspan="11"><h4
                        class="text-warning">{{__('dashboard.Shopping Bonus Rates')}}</h4>
            </td>
        </tr>
        <tr>
            <td colspan="11">{{__('dashboard.Total')}} 100%</td>
        </tr>
        <tr>
            <td colspan="2">{{__('dashboard.Merchant Rate')}}</td>
            <td colspan="9">{{__('dashboard.Admin Rate')}}

                <i class="pull-right">
                    {{__('dashboard.Customer')}} -
                <span class="shopping-content"
                      data-key="customer_bonus">{{$rates['customer_bonus']->value}}</span>% {{__('front.of')}} {{__('dashboard.Admin Rate')}}
                </i>
            </td>
        </tr>
        <tr>
            <td colspan="2" rowspan="6"> - - -</td>
            <td colspan="9"> - - -</td>
        </tr>
        <tr>
            <td colspan="6">{{__('dashboard.Admin')}}</td>
            <td colspan="3">{{$rates['shopping_bonus_rate']->name}}</td>
        </tr>
        <tr>
            <td colspan="6">
                                                <span class="shopping-content"
                                                      data-key="minus_shopping_bonus_rate">{{100 - $rates['shopping_bonus_rate']->value}}</span>%
            </td>
            <td colspan="3">
                                                <span class="shopping-content"
                                                      data-key="shopping_bonus_rate">{{$rates['shopping_bonus_rate']->value}}</span>%
            </td>
        </tr>
        <tr>
            <td colspan="3">{{$rates['admin_rate']->name}}</td>
            <td colspan="3">{{$rates['bonus_rate']->name}}</td>
            <td colspan="2" rowspan="3">
                {{$rates['standard_shopping_bonus']->name}}
                <br>{{$rates['auto_shopping_bonus']->name}}
                <br>{{$rates['special_shopping_bonus']->name}}
                <br> <br>{{$rates['ecash_shopping_bonus']->name}}
                <br>{{$rates['evoucher_shopping_bonus']->name}}
                <br>{{$rates['bcoin_shopping_bonus']->name}}
            </td>
            <td colspan="1" rowspan="3">
                                                <span class="shopping-content"
                                                      data-key="standard_shopping_bonus">{{$rates['standard_shopping_bonus']->value}}</span>%
                <br><span class="shopping-content"
                          data-key="auto_shopping_bonus">{{$rates['auto_shopping_bonus']->value}}</span>%
                <br><span class="shopping-content"
                          data-key="special_shopping_bonus">{{$rates['special_shopping_bonus']->value}}</span>%
                <br> <br><span class="shopping-content"
                               data-key="ecash_shopping_bonus">{{$rates['ecash_shopping_bonus']->value}}</span>%
                <br><span class="shopping-content"
                          data-key="evoucher_shopping_bonus">{{$rates['evoucher_shopping_bonus']->value}}</span>%
                <br><span class="shopping-content"
                          data-key="bcoin_shopping_bonus">{{$rates['bcoin_shopping_bonus']->value}}</span>%
            </td>
        </tr>
        <tr>
            <td colspan="3" rowspan="2">
                                                <span class="shopping-content"
                                                      data-key="admin_rate">{{$rates['admin_rate']->value}}</span>%
            <td colspan="3">
                                                <span class="shopping-content"
                                                      data-key="bonus_rate">{{$rates['bonus_rate']->value}}</span>%
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {{$rates['hk_bonus']->name}}
                <br> {{$rates['asia_bonus']->name}}
                <br> {{$rates['top_shopper_bonus']->name}}
                <br> <br> {{$rates['ecash_bonus']->name}}
                <br> {{$rates['evoucher_bonus']->name}}
                <br> {{$rates['bcoin_bonus']->name}}
            </td>
            <td colspan="1">
                                                <span class="shopping-content"
                                                      data-key="hk_bonus">{{$rates['hk_bonus']->value}}</span>%
                <br> <span class="shopping-content"
                           data-key="asia_bonus">{{$rates['asia_bonus']->value}}</span>%
                <br> <span class="shopping-content"
                           data-key="top_shopper_bonus">{{$rates['top_shopper_bonus']->value}}</span>%
                <br><br> <span class="shopping-content"
                               data-key="ecash_bonus">{{$rates['ecash_bonus']->value}}</span>%
                <br> <span class="shopping-content"
                           data-key="evoucher_bonus">{{$rates['evoucher_bonus']->value}}</span>%
                <br> <span class="shopping-content"
                           data-key="bcoin_bonus">{{$rates['bcoin_bonus']->value}}</span>%
            </td>
        </tr>
    </table>
    <span class="alert fade" id="message-shopping"></span>
    <button class="btn blue" id="edit-shopping"><i class="fa fa-edit"></i>
        {{__('dashboard.Edit')}}
    </button>
    <button class="shopping-btn btn blue fade" id="save-shopping"><i
                class="fa fa-save"></i> {{__('dashboard.Save')}}
    </button>
    <br>
    <br>
</div>

