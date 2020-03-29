<?php

namespace App\Http\Controllers;

use App\Desk;
use App\Import;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Shop;
use App\Position;
use App\Supplier;
use App\Zone;
use App\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Hashids\Hashids;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getLogin()
    {
        if (Auth::check()) {
            return redirect()->route('/');
        } else
            return view('login');
    }

    public function postLogin(Request $req)
    {
        $this->validate(
            $req,
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'Vui lòng nhập tên đăng nhập',
                'password.required' => 'Vui lòng nhập mật khẩu',
            ]
        );
        $credentials = array('email' => $req->username, 'password' => $req->password);
        if (Auth::attempt($credentials, $req->has('remember'))) {
            return redirect()->route('/');
        } else return redirect()->route('login')->withInput()->with('message', 'Tên đăng nhập hoặc mật khẩu không chính xác.');
    }

    public function getRegister()
    {
        if (Auth::check()) {
            return redirect()->route('/');
        } else
            return view('register');
    }

    public function postRegister(Request $req)
    {
        $this->validate($req, [
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'repassword' => 'required|same:password',
            'name' => 'required|min:10|max:50',
            'address' => 'required|max:150',
            'birthday' => 'required|min:10',
            'phone' => 'required|min:12',
            'shopname' => 'required|max:50',
            'shopaddress' => 'required|max:150',
        ], [
            'email.unique' => 'Tên đăng nhập "' . ucwords($req->email) . '" đã được đăng ký.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự',
            'repassword.same' => 'Xác nhận mật khẩu không trùng khớp',
            'name.min' => 'Họ tên tối thiểu 10 ký tự',
            'name.max' => 'Họ tên tối đa 50 ký tự',
            'address.max' => 'Địa chỉ tối đa 150 ký tự',
            'birthday.min' => 'Ngày sinh không đúng định dạng',
            'phone.min' => 'Số điện thoại không đúng định dạng',
            'shopname.max' => 'Tên cửa hàng tối đa 50 ký tự',
            'shopaddress.max' => 'Địa chỉ cửa hàng tối đa 150 ký tự',
        ]);

        try {
            $shop = new Shop;
            $user = new User;

            $shop->shopname = $req->shopname;
            $shop->shopaddress = $req->shopaddress;
            $shop->save();

            $user->email = $req->email;
            $user->password = bcrypt($req->password);
            $user->name = $req->name;
            $user->address = $req->address;
            $user->birthday = $req->birthday;
            $user->phone = $req->phone;
            $user->startday = Carbon::now()->format('d/m/Y');
            $user->basesalary = 0;
            $user->posid = 1;
            $user->shopid = Shop::latest('shopid')->first()->shopid;
            $user->save();

            return view('login')->with('message', 'Tạo tài khoản thành công!!');
        } catch (Exception $e) {
            return redirect()->view('register')->withInput();
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->back();
    }

    public function getPosition(Request $req)
    {
        $positions = Position::where('posname', 'like', '%' . $req->search . '%')->where('shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('position', compact('positions', 'search'));
    }

    public function postNewPosition(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'posname' => 'required|max:50|unique:position',
                'coefficient' => 'required|numeric|min:0|max:20'
            ],
            [
                'posname.required' => 'Vui lòng nhập tên chức vụ',
                'posname.unique' => 'Chức vụ "' . ucwords($req->posname) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'coefficient.numeric' => 'Hệ số lương phải là số',
                'coefficient.required' => 'Vui lòng nhập hệ số lương',
                'coefficient.min' => 'Hệ số lương phải lớn hơn 0',
                'coefficient.max' => 'Hệ số lương phải nhỏ hơn 20'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewPosition_Error')->withInput();
        }

        try {
            $pos = new Position;
            $pos->posname = $req->posname;
            $pos->coefficient = $req->coefficient;
            $pos->shopid = Auth::user()->shopid;
            $pos->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditPosition(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'posnameedit' => ['required', 'max:50', Rule::unique('position', 'posname')->ignore($req->idpos, 'idpos')],
                'coefficientedit' => 'required|numeric|min:0|max:20'
            ],
            [
                'posnameedit.required' => 'Vui lòng nhập tên chức vụ',
                'posnameedit.unique' => 'Chức vụ "' . ucwords($req->posnameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'coefficientedit.numeric' => 'Hệ số lương phải là số',
                'coefficientedit.required' => 'Vui lòng nhập hệ số lương',
                'coefficientedit.min' => 'Hệ số lương phải lớn hơn 0',
                'coefficientedit.max' => 'Hệ số lương phải nhỏ hơn 20'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditPosition_Error')->withInput();
        }

        try {
            $pos = Position::where('idpos', $req->idpos)->first();
            $pos->posname = $req->posnameedit;
            $pos->coefficient = $req->coefficientedit;
            $pos->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeletePosition(Request $req)
    {
        $pos = Position::where('idpos', $req->idposdel)->first();
        $user = User::where('posid', $pos->idpos)->get();
        if ($user->count() == 0) {
            $pos->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getEmployee(Request $req)
    {
        $employees = User::join('position', 'position.idpos', '=', 'users.posid')
            ->where('users.shopid', Auth::user()->shopid)->where('name', 'like', '%' . $req->search . '%')->paginate(30);
        $positions = Position::where('shopid', Auth::user()->shopid)->get();

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $string = str_shuffle($pin);
        $hashids = new Hashids($string);
        $gener_username = $hashids->encode(1, 2);
        $gener_pass = $hashids->encode(1, 2, 3, 4);
        $search = $req->search;

        return view('employee', compact('employees', 'gener_username', 'gener_pass', 'positions', 'search'));
    }

    public function postNewEmployee(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'email' => 'required|unique:users',
                'password' => 'required|min:8',
                'name' => 'required|min:10|max:50',
                'address' => 'required|max:150',
                'birthday' => 'required|min:10',
                'phone' => 'required|min:12',
                'startday' => 'required|min:10',
                'basesalary' => 'required|min:0|max:100000000',
                'idpos' => 'numeric|not_in:position,idpos',

            ],
            [
                'email.unique' => 'Tên đăng nhập "' . ucwords($req->email) . '" đã được đăng ký',
                'password.min' => 'Mật khẩu tối thiểu 8 ký tự',
                'name.min' => 'Họ tên tối thiểu 10 ký tự',
                'name.max' => 'Họ tên tối đa 50 ký tự',
                'address.max' => 'Địa chỉ tối đa 150 ký tự',
                'birthday.min' => 'Ngày sinh không đúng định dạng',
                'phone.min' => 'Số điện thoại không đúng định dạng',
                'startday.min' => 'Ngày vào làm không đúng định dạng',
                'basesalary.min' => 'Lương cơ bản tối thiểu 0đ',
                'basesalary.max' => 'Lương cơ bản tối đa 100.000.000đ',
                'idpos.not_in' => 'Không có chức vụ nào phù hợp với lựa chọn của bạn',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewEmployee_Error')->withInput();
        }

        try {
            $user = new User;
            $salary = str_replace(',', '', str_replace(' ₫', '', $req->basesalary));

            $user->email = $req->email;
            $user->password = bcrypt($req->password);
            $user->name = $req->name;
            $user->address = $req->address;
            $user->birthday = $req->birthday;
            $user->phone = $req->phone;
            $user->startday = $req->startday;
            $user->basesalary = $salary;
            $user->posid = $req->idpos;
            $user->shopid = Auth::user()->shopid;
            $user->save();

            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditEmployee(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'nameedit' => 'required|min:10|max:50',
                'addressedit' => 'required|max:150',
                'birthdayedit' => 'required|min:10',
                'phoneedit' => 'required|min:12',
                'startdayedit' => 'required|min:10',
                'basesalaryedit' => 'required|min:0|max:100000000',
                'idposedit' => 'numeric|not_in:position,idpos',

            ],
            [
                'nameedit.min' => 'Họ tên tối thiểu 10 ký tự',
                'nameedit.max' => 'Họ tên tối đa 50 ký tự',
                'addressedit.max' => 'Địa chỉ tối đa 150 ký tự',
                'birthdayedit.min' => 'Ngày sinh không đúng định dạng',
                'phoneedit.min' => 'Số điện thoại không đúng định dạng',
                'startdayedit.min' => 'Ngày vào làm không đúng định dạng',
                'basesalaryedit.min' => 'Lương cơ bản tối thiểu 0đ',
                'basesalaryedit.max' => 'Lương cơ bản tối đa 100.000.000đ',
                'idposedit.not_in' => 'Không có chức vụ nào phù hợp với lựa chọn của bạn',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditEmployee_Error')->withInput();
        }

        try {
            $user = User::where('id', $req->iduser)->first();
            $salary = str_replace(',', '', str_replace(' ₫', '', $req->basesalaryedit));

            $user->name = $req->nameedit;
            $user->address = $req->addressedit;
            $user->birthday = $req->birthdayedit;
            $user->phone = $req->phoneedit;
            $user->startday = $req->startdayedit;
            $user->basesalary = $salary;
            $user->posid = $req->idposedit;
            $user->update();

            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteEmployee(Request $req)
    {
        $user = User::where('id', $req->iduserdel)->first();
        if ($user->count() > 0) {
            $user->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getZone(Request $req)
    {
        $zones = Zone::where('zonename', 'like', '%' . $req->search . '%')->where('shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('zone', compact('zones', 'search'));
    }

    public function postNewZone(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'zonename' => 'required|max:100|unique:zone'
            ],
            [
                'zonename.required' => 'Vui lòng nhập tên khu vực',
                'zonename.unique' => 'Khu vực "' . ucwords($req->zonename) . '" đã tồn tại. Vui lòng thử lại với tên khác',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewZone_Error')->withInput();
        }

        try {
            $zone = new Zone;
            $zone->zonename = $req->zonename;
            $zone->shopid = Auth::user()->shopid;
            $zone->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditZone(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'zonenameedit' => ['required', 'max:100', Rule::unique('zone', 'zonename')->ignore($req->idzone, 'idzone')]
            ],
            [
                'zonenameedit.required' => 'Vui lòng nhập tên khu vực',
                'zonenameedit.unique' => 'Khu vực "' . ucwords($req->zonenameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditZone_Error')->withInput();
        }

        try {
            $zone = Zone::where('idzone', $req->idzone)->first();
            $zone->zonename = $req->zonenameedit;
            $zone->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteZone(Request $req)
    {
        $zone = Zone::where('idzone', $req->idzonedel)->first();
        $desk = Desk::where('zoneid', $zone->idzone)->get();
        if ($desk->count() == 0) {
            $zone->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getDesk(Request $req)
    {
        $desks = Desk::join('zone', 'zone.idzone', '=', 'desk.zoneid')
            ->where('deskname', 'like', '%' . $req->search . '%')->where('desk.shopid', Auth::user()->shopid)->get();
        $zones = Zone::where('shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('desk', compact('desks', 'search', 'zones'));
    }

    public function postNewDesk(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'deskname' => 'required|max:50|unique:desk',
                'idzone' => 'numeric|not_in:zone,idzone',
            ],
            [
                'deskname.required' => 'Vui lòng nhập tên bàn',
                'deskname.unique' => 'Bàn "' . ucwords($req->deskname) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'idzone.not_in' => 'Không có khu vực nào phù hợp với lựa chọn của bạn',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewDesk_Error')->withInput();
        }

        try {
            $desk = new Desk;
            $desk->deskname = $req->deskname;
            $desk->zoneid = $req->idzone;
            $desk->shopid = Auth::user()->shopid;
            $desk->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditDesk(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'desknameedit' => ['required', 'max:50', Rule::unique('desk', 'deskname')->ignore($req->iddesk, 'iddesk')],
                'idzoneedit' => 'numeric|not_in:zone,idzone',
            ],
            [
                'desknameedit.required' => 'Vui lòng nhập tên bàn',
                'desknameedit.unique' => 'Bàn "' . ucwords($req->desknameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'idzoneedit.not_in' => 'Không có khu vực nào phù hợp với lựa chọn của bạn',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditDesk_Error')->withInput();
        }

        try {
            $desk = Desk::where('iddesk', $req->iddesk)->first();
            $desk->deskname = $req->desknameedit;
            $desk->zoneid = $req->idzoneedit;
            $desk->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteDesk(Request $req)
    {
        $desk = Desk::where('iddesk', $req->iddeskdel)->first();
        $desk->delete();
        return redirect()->back()->with('succ', '');
    }

    public function getVoucher(Request $req)
    {
        $vouchers = Voucher::where('vouchername', 'like', '%' . $req->search . '%')->where('voucher.shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('voucher', compact('vouchers', 'search'));
    }

    public function postNewVoucher(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'vouchername' => 'required|max:100|unique:voucher',
                'sale' => 'required|numeric',
                'startday' => 'required',
                'endday' => 'required'
            ],
            [
                'vouchername.required' => 'Vui lòng nhập tên khuyến mãi',
                'vouchername.unique' => 'Khuyến mãi "' . ucwords($req->vouchername) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'sale.required' => 'Vui lòng nhập giảm giá',
                'sale.numeric' => 'Giảm giá phải là số',
                'startday.required' => 'Vui lòng nhập ngày bắt đầu',
                'endday.required' => 'Vui lòng nhập ngày kết thúc'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewVoucher_Error')->withInput();
        }

        try {
            $voucher = new Voucher;
            $voucher->vouchername = $req->vouchername;
            $voucher->sale = $req->sale;
            $voucher->startday = $req->startday;
            $voucher->endday = $req->endday;
            $voucher->shopid = Auth::user()->shopid;
            $voucher->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditVoucher(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'vouchernameedit' => ['required', 'max:100', Rule::unique('voucher', 'vouchername')->ignore($req->idvoucher, 'idvoucher')],
                'saleedit' => 'required|numeric',
                'startdayedit' => 'required',
                'enddayedit' => 'required'
            ],
            [
                'vouchernameedit.required' => 'Vui lòng nhập tên khuyến mãi',
                'vouchernameedit.unique' => 'Khuyến mãi "' . ucwords($req->vouchernameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'saleedit.required' => 'Vui lòng nhập giảm giá',
                'sale.numeric' => 'Giảm giá phải là số',
                'startdayedit.required' => 'Vui lòng nhập ngày bắt đầu',
                'enddayedit.required' => 'Vui lòng nhập ngày kết thúc'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewVoucher_Error')->withInput();
        }

        try {
            $voucher = Voucher::where('idvoucher', $req->idvoucher)->first();
            $voucher->vouchername = $req->vouchernameedit;
            $voucher->sale = $req->saleedit;
            $voucher->startday = $req->startdayedit;
            $voucher->endday = $req->enddayedit;
            $voucher->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteVoucher(Request $req)
    {
        $voucher = Voucher::where('idvoucher', $req->idvoucherdel)->first();
        $voucher->delete();
        return redirect()->back()->with('succ', '');
    }

    public function getSupplier(Request $req)
    {
        $suppliers = Supplier::where('suppname', 'like', '%' . $req->search . '%')->where('supplier.shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('supplier', compact('suppliers', 'search'));
    }

    public function postNewSupplier(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'suppname' => 'required|max:50|unique:supplier',
                'suppaddress' => 'required|max:100',
                'suppphone' => 'required|min:12'
            ],
            [
                'suppname.required' => 'Vui lòng nhập tên nhà cung cấp',
                'suppname.unique' => 'Nhà cung cấp "' . ucwords($req->suppname) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'suppaddress.required' => 'Vui lòng nhập địa chỉ',
                'suppaddress.max' => 'Địa chỉ tối đa 100 ký tự',
                'suppphone.required' => 'Vui lòng nhập số điện thoại',
                'suppphone.min' => 'Số điện thoại không đúng định dạng'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewSupplier_Error')->withInput();
        }

        try {
            $supp = new Supplier;
            $supp->suppname = $req->suppname;
            $supp->suppaddress = $req->suppaddress;
            $supp->suppphone = $req->suppphone;
            $supp->shopid = Auth::user()->shopid;
            $supp->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditSupplier(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'suppnameedit' => ['required', 'max:50', Rule::unique('supplier', 'suppname')->ignore($req->idsupp, 'idsupp')],
                'suppaddressedit' => 'required|max:100',
                'suppphoneedit' => 'required|min:12'
            ],
            [
                'suppnameedit.required' => 'Vui lòng nhập tên nhà cung cấp',
                'suppnameedit.unique' => 'Nhà cung cấp "' . ucwords($req->suppnameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'suppaddressedit.required' => 'Vui lòng nhập địa chỉ',
                'suppaddressedit.max' => 'Địa chỉ tối đa 100 ký tự',
                'suppphoneedit.required' => 'Vui lòng nhập số điện thoại',
                'suppphoneedit.min' => 'Số điện thoại không đúng định dạng'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditSupplier_Error')->withInput();
        }

        try {
            $supp = Supplier::where('idsupp',$req->idsupp)->first();
            $supp->suppname = $req->suppnameedit;
            $supp->suppaddress = $req->suppaddressedit;
            $supp->suppphone = $req->suppphoneedit;
            $supp->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteSupplier(Request $req)
    {
        $supp = Supplier::where('idsupp', $req->idsuppdel)->first();
        $imp = Import::where('suppid', $supp->idsupp)->get();
        if ($imp->count() == 0) {
            $supp->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }





}
