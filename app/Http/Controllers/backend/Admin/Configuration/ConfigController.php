<?php

namespace App\Http\Controllers\backend\Admin\Configuration;

use App\Models\ChipsConfig;
use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingLog;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\GenerationBonusDistribution;
use App\Models\Holiday;
use App\Models\Members\ReferalBonusRegister;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;

class ConfigController extends Controller
{
    private $_path = 'backend.admin.configs.';
    private $_data = [];
    /**
     * @var Package
     */
    private $package;

    public function __construct(Package $package)
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
        $this->package = $package;
    }


    public function getPackage()
    {
        $this->_data['packages'] = Package::paginate(10);
        $this->_data['chipConfig'] = ChipsConfig::first();
        return view($this->_path . 'packages.packageList', $this->_data);
    }

    public function editPackage($packId)
    {
        $this->_data['package'] = Package::find($packId);
        return view($this->_path . 'packages.edit-package', $this->_data);
    }

    public function updatePackage(Request $request)
    {

        $status = true;


        foreach ($request->input as $item) {

            if (intval($item['value']) < 0) {
                $status = false;
                $message = __('message.Not Saved! Value should be Greater than 0.');
            }
        }
        if ($status) {
            foreach ($request->input as $input) {
                $updatePackage = Package::where('name', $input['name'])->first();
                $pak = Package::all();
                $updateChip = ChipsConfig::first();
                if ($updatePackage) {
                    $updatePackage->update(['amount' => $input['value']]);
                }
                if ($updateChip) {
                    if ($input['name'] == 'price_per_chips') {
                        $updateChip->update([$input['name'] => $input['value']]);
                    }

                }
                if ($input['name'] == 'capital_value_Gold') {
                    $this->package->where('name', '=', 'Gold')->update(['capital_value' => $input['value']]);
                }
                if ($input['name'] == 'capital_value_Platinum') {
                    $this->package->where('name', '=', 'Platinum')->update(['capital_value' => $input['value']]);
                }
                if ($input['name'] == 'capital_value_Diamond') {
                    $this->package->where('name', '=', 'Diamond')->update(['capital_value' => $input['value']]);
                }
                if ($input['name'] == 'dividend_Gold') {
                    $this->package->where('name', '=', 'Gold')->update(['dividend' => $input['value']]);
                }
                if ($input['name'] == 'dividend_Platinum') {
                    $this->package->where('name', '=', 'Platinum')->update(['dividend' => $input['value']]);
                }
                if ($input['name'] == 'dividend__Diamond') {
                    $this->package->where('name', '=', 'Diamond')->update(['dividend' => $input['value']]);
                }
            }
            return response()->json(['status' => true, 'message' => __('message.Successfully saved')]);
        }
        return response()->json(['status' => false, 'message' => $message]);
    }

    function bonusList()
    {
        $this->_data['bonus'] = ReferalBonusRegister::all();
        $this->_data['distribution'] = GenerationBonusDistribution::first();
        return view($this->_path . 'refaral-bonus-list', $this->_data);
    }

    public function addBonus()
    {
        $this->_data['packages'] = Package::where('status', 1)->pluck('name', 'id');
        return view($this->_path . 'add-referral', $this->_data);
    }

    public function storeBonus(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'generation_position' => 'required|integer',
            'package_id' => 'required|integer',
            'refaral_percentage' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        $data = [
            'generation_position' => $inputs['generation_position'],
            'package_id' => $inputs['package_id'],
            'refaral_percentage' => $inputs['refaral_percentage'],

        ];
        if (ReferalBonusRegister::create($data)) {
            return redirect()->to(route('add-refaral-bonus'))->with('success', __('message.Referal bonus created successfully'));
        }

    }

    public function editBonus($refID)
    {
        $this->_data['referal'] = ReferalBonusRegister::find($refID);
        $this->_data['packages'] = Package::where('status', 1)->pluck('name', 'id');
        return view($this->_path . 'edit-referral', $this->_data);
    }

    public function updateBonus(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'generation_position' => 'required|integer',
            'package_id' => 'required|integer',
            'refaral_percentage' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        $refID = $inputs['refID'];
        $data = array(
            'generation_position' => $inputs['generation_position'],
            'package_id' => $inputs['package_id'],
            'refaral_percentage' => $inputs['refaral_percentage'],
        );
        $update_data = ReferalBonusRegister::where('id', $refID)->update($data);
        return redirect()->back()->with('success', __('message.Referal bonus updated successfully'));
    }


    public function deleteBonus($refId)
    {
        try {
            $data = ReferalBonusRegister::where('id', $refId)->delete();
            return redirect()->back()->with('success', __('message.Referal bonus removed successfully'));
        } catch (ModelNotFoundException $ex) {
            $result = $ex->getMessage();
            return redirect()->back()->with('fail', __('message.That data does not exist'));
        }
    }

    public function holiDays()
    {
        $this->_data['holidays'] = Holiday::paginate(10);
        return view($this->_path . 'holidays.list', $this->_data);
    }

    public function addHoliday()
    {
        return view($this->_path . 'holidays.add-holiday', $this->_data);
    }

    public function storeHoliday(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'holiday_date' => 'required|unique:holiday',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        $data = [
            'holiday_date' => $inputs['holiday_date'],

        ];
        if (Holiday::create($data)) {
            return redirect()->to(route('add-holiday'))->with('success', __('message.Holiday created successfully'));
        }

    }

    public function editHoliday($id)
    {
        $this->_data['holiday'] = Holiday::find($id);
        return view($this->_path . 'holidays.edit-holiday', $this->_data);
    }

    public function updateHoliday(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'holiday_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        $holidayId = $inputs['holiday_id'];
        $data = array(
            'holiday_date' => $inputs['holiday_date'],

        );
        $update_data = Holiday::where('id', $holidayId)->update($data);
        return redirect()->back()->with('success', __('message.Holiday updated successfully'));
    }

    public function deleteHoliday($id)
    {
        try {
            $data = Holiday::where('id', $id)->delete();
            return redirect()->back()->with('success', __('message.Holiday removed successfully'));
        } catch (ModelNotFoundException $ex) {
            $result = $ex->getMessage();
            return redirect()->back()->with('fail', __('message.That data does not exist'));
        }
    }

    function updateSingleReferral(Request $request)
    {
        $status = true;
        foreach ($request->input as $item) {
            if (!is_numeric($item['value'])) {
                $status = false;
                $message = __('message.Not Saved! Value should be Numeric.');
            }
            if (intval($item['value']) > 100) {
                $status = false;
                $message = __('message.Not Saved! Value should be Less than 100.');
            }
            if (intval($item['value']) < 0) {
                $status = false;
                $message = __('message.Not Saved! Value should be Greater than 0.');
            }
        }
        if ($status) {
            foreach ($request->input as $input) {
                switch (strtolower($input['type'])) {
                    case 'distribution':
                        $updateShopping = GenerationBonusDistribution::first();
                        if ($updateShopping) {
                            $updateShopping->update([$input['key'] => $input['value']]);
                        }
                        break;
                    case 'referal':
                        $updateShopping = ReferalBonusRegister::where('generation_position', $input['generation'])
                            ->where('package_id', $input['package'])->first();
                        if ($updateShopping) {
                            $updateShopping->update(['refaral_percentage' => $input['value']]);
                        } else {
                            ReferalBonusRegister::create([
                                'generation_position' => $input['generation'],
                                'package_id' => $input['package'],
                                'refaral_percentage' => $input['value']
                            ]);
                        }
                        break;
                    default:
                        break;
                }
            }
            return response()->json(['status' => true, 'message' => __('message.Successfully saved')]);
        }
        return response()->json(['status' => false, 'message' => $message]);

    }

    function holidayDates(Request $request)
    {
        $holidays = Holiday::all();

        $calendar = Calendar::setId('trading')->setOptions(['header' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'prevYear,nextYear',
        ],
            'eventLimit' => true,
            'selectable' => true,
            'unselectAuto' => false,
            'defaultDate' => Carbon::parse($request->date)->toDateString(),

        ])->setCallbacks([
            'select' => 'function(selectionInfo) {
         selectDateCalendar(selectionInfo);
         }',
            'eventClick' => 'function(selectionInfo){
           removeDateCalendar(selectionInfo);
         }',
//            'unselect' => 'function(selectionInfo){
//            unselectDateCalendar(selectionInfo);
//            }'
        ]);


        $events = [];
        foreach ($holidays as $holiday) {
            $events[] = $calendar->event(
                $holiday->name,
                true,
                Carbon::parse($holiday->holiday_date)->toDateTimeString(),
                Carbon::parse($holiday->holiday_date)->toDateTimeString(),
                $holiday->id,
                [
//                    'url' => url('admin/config/edit-holiday/'.$holiday->id ),
                    'color' => '#eb4955',
                ]
            );
        }
        $this->_data['calendar_view'] = $calendar->addEvents($events);
        return view($this->_path . 'holidays.calendar', $this->_data);
    }

    function addCalendarEvent(Request $request)
    {
        $valid = Validator::make($request->all(), ['date' => 'required']);
        if ($valid->fails())
            return response()->json(['status' => false]);

        Holiday::create(['holiday_date' => Carbon::parse($request->date)]);
        return response()->json(['status' => true]);
    }

    function removeCalendarEvent(Request $request)
    {
        $valid = Validator::make($request->all(), ['id' => 'required']);
        if ($valid->fails())
            return response()->json(['status' => false]);

        $holiday = Holiday::find($request->id);
        if ($holiday) {
//            if ($holiday->holiday_date > Carbon::now()) {
                $holiday->delete();
                return response()->json(['status' => true]);
//            }
        }
        return response()->json(['status' => false]);
    }

    function checkCalendarEvent(Request $request)
    {
        $valid = Validator::make($request->all(), ['date' => 'required']);
        if ($valid->fails())
            return response()->json(['status' => false]);

//        if (Carbon::parse($request->date) < Carbon::now())
//            return response()->json(['exist' => false]);

        $holiday = Holiday::where('holiday_date', Carbon::parse($request->date))->first();
        if ($holiday)
            return response()->json(['exist' => false]);

        return response()->json(['exist' => true]);

    }
}
