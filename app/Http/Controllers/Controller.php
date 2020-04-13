<?php

namespace App\Http\Controllers;

use App\Bill;
use App\BillDetail;
use App\Desk;
use App\Formula;
use App\Import;
use App\ImportDetail;
use App\Material;
use App\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Shop;
use App\Position;
use App\Product;
use App\ProductCate;
use App\Supplier;
use App\Zone;
use App\Voucher;
use App\Workday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDashboard()
    {
        return view('dashboard');
    }

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
            $pos = new Position;
            $per = new Permission;
            $perarr = ['position', 'employee', 'zone', 'desk', 'supplier', 'material', 'import', 'productcate', 'product', 'voucher', 'workday', 'sell'];
            $permission = [];

            $shop->shopname = $req->shopname;
            $shop->shopaddress = $req->shopaddress;
            $shop->save();


            foreach ($perarr as $arr) {
                if ($arr == 'sell') {
                    $permission[$arr . '.view'] = true;
                    $permission[$arr . '.create'] = true;
                    $permission[$arr . '.delete'] = true;
                    $permission[$arr . '.pay'] = true;
                    $permission[$arr . '.merge'] = true;
                } else {
                    $permission[$arr . '.view'] = true;
                    $permission[$arr . '.create'] = true;
                    $permission[$arr . '.update'] = true;
                    $permission[$arr . '.delete'] = true;
                }
            }
            $permission['position.role'] = true;
            $pos->posname = 'Admin';
            $pos->coefficient = '0';
            $pos->shopid = $shop->idshop;
            $pos->permissions = $permission;
            $pos->save();

            $user->email = $req->email;
            $user->password = bcrypt($req->password);
            $user->name = $req->name;
            $user->address = $req->address;
            $user->birthday = $req->birthday;
            $user->phone = $req->phone;
            $user->startday = Carbon::now()->format('d/m/Y');
            $user->basesalary = 0;
            $user->shopid = $shop->idshop;
            $user->save();

            $per->user_id = $user->id;
            $per->position_idpos = $pos->idpos;
            $per->save();

            return view('login')->with('message', 'Tạo tài khoản thành công!!');
        } catch (\Throwable $th) {
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
                'posname' => ['required', 'max:50', Rule::unique('position')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })],
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
            $perarr = ['position', 'employee', 'zone', 'desk', 'supplier', 'material', 'import', 'productcate', 'product', 'voucher', 'workday', 'sell'];
            $permission = [];
            foreach ($perarr as $arr) {
                if ($arr == 'sell') {
                    $permission[$arr . '.view'] = false;
                    $permission[$arr . '.create'] = false;
                    $permission[$arr . '.delete'] = false;
                    $permission[$arr . '.pay'] = false;
                    $permission[$arr . '.merge'] = false;
                } else {
                    $permission[$arr . '.view'] = false;
                    $permission[$arr . '.create'] = false;
                    $permission[$arr . '.update'] = false;
                    $permission[$arr . '.delete'] = false;
                }
            }
            $permission['position.role'] = false;
            $pos = new Position;
            $pos->posname = $req->posname;
            $pos->coefficient = $req->coefficient;
            $pos->shopid = Auth::user()->shopid;
            $pos->permissions = $permission;
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
                'posnameedit' => ['required', 'max:50', Rule::unique('position', 'posname')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->idpos, 'idpos')],
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
        $per = Permission::where('position_idpos', $pos->idpos)->get();
        if ($per->count() == 0) {
            $pos->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getEmployee(Request $req)
    {
        $temp = User::join('permission', 'permission.user_id', 'users.id')
            ->where('users.shopid', Auth::user()->shopid)->where('name', 'like', '%' . $req->search . '%');
        $employees = Position::joinSub($temp, 'employ', function ($join) {
            $join->on('position.idpos', 'employ.position_idpos');
        })->paginate(30);
        $positions = Position::where('shopid', Auth::user()->shopid)->get();

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pin = mt_rand(1000000, 9999999)
            . mt_rand(1000000, 9999999)
            . $characters[rand(0, strlen($characters) - 1)];
        $gener_username = str_shuffle($pin);
        $gener_pass = $gener_username;
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
            $user->shopid = Auth::user()->shopid;
            $user->save();

            $per = new Permission;
            $per->position_idpos = $req->idpos;
            $per->user_id = $user->id;
            $per->save();

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
            $user->update();

            $per = Permission::where('user_id', $user->id)->first();
            $per->position_idpos = $req->idposedit;
            $per->update();

            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteEmployee(Request $req)
    {
        $user = User::where('id', $req->iduserdel)->first();
        $imp = Import::where('userid', $user->id)->first();
        $wd = Workday::where('userid', $user->id)->first();
        $bill = Bill::where('userid', $user->id)->first();
        if (!$imp && !$wd && !$bill) {
            Permission::where('user_id', $user->id)->delete();
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
                'zonename' => ['required', 'max:100', Rule::unique('zone')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })]
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
                'zonenameedit' => ['required', 'max:100', Rule::unique('zone', 'zonename')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->idzone, 'idzone')]
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
                'deskname' => ['required', 'max:50', Rule::unique('desk')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })],
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
                'desknameedit' => ['required', 'max:50', Rule::unique('desk', 'deskname')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->iddesk, 'iddesk')],
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
        $bill = Bill::where('deskid', $desk->iddesk)->get();
        if ($bill->count() == 0) {
            $desk->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
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
                'vouchername' => ['required', 'max:100', Rule::unique('voucher')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })],
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
            $a = explode('/', $req->startday);
            $startday = Carbon::create($a[2], $a[1], $a[0]);
            $b = explode('/', $req->endday);
            $endday = Carbon::create($b[2], $b[1], $b[0]);

            $voucher = new Voucher;
            $voucher->vouchername = $req->vouchername;
            $voucher->sale = $req->sale;
            $voucher->startday = $startday;
            $voucher->endday = $endday;
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
                'vouchernameedit' => ['required', 'max:100', Rule::unique('voucher', 'vouchername')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->idvoucher, 'idvoucher')],
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
            $a = explode('/', $req->startdayedit);
            $startday = Carbon::create($a[2], $a[1], $a[0]);
            $b = explode('/', $req->enddayedit);
            $endday = Carbon::create($b[2], $b[1], $b[0]);

            $voucher = Voucher::where('idvoucher', $req->idvoucher)->first();
            $voucher->vouchername = $req->vouchernameedit;
            $voucher->sale = $req->saleedit;
            $voucher->startday = $startday;
            $voucher->endday = $endday;
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
                'suppname' => ['required', 'max:50', Rule::unique('supplier')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })],
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
                'suppnameedit' => ['required', 'max:50', Rule::unique('supplier', 'suppname')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->idsupp, 'idsupp')],
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
            $supp = Supplier::where('idsupp', $req->idsupp)->first();
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

    public function getWorkday(Request $req)
    {
        $dayfrom = Carbon::today('Asia/Ho_Chi_Minh');
        $dayto = Carbon::today('Asia/Ho_Chi_Minh');
        if ($req->dayfrom && $req->dayto) {
            $a = explode('/', $req->dayfrom);
            $b = explode('/', $req->dayto);
            $dayfrom = Carbon::create($a[2], $a[1], $a[0]);
            $dayto = Carbon::create($b[2], $b[1], $b[0]);
        }
        if ($dayfrom == $dayto) {
            $wds = Workday::join('users', 'users.id', '=', 'workday.userid')->where('workday.shopid', Auth::user()->shopid)
                ->where('wddate', '<=', $dayto)->where('wddate', '>=', $dayfrom)->get();
        } else {
            $wds = Workday::selectRaw('name, sum(hour) as hour')
                ->join('users', 'users.id', '=', 'workday.userid')->where('workday.shopid', Auth::user()->shopid)
                ->where('wddate', '<=', $dayto)->where('wddate', '>=', $dayfrom)->groupBy('name')->get();
        }
        $users = User::where('shopid', Auth::user()->shopid)->get();
        return view('workday', compact('wds', 'users', 'dayfrom', 'dayto'));
    }

    public function postNewWorkday(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'iduser' => 'required|max:50|not_in:users,id',
                'hour' => 'required|numeric',
                'wddate' => 'required|min:10'
            ],
            [
                'iduser.required' => 'Vui lòng chọn nhân viên',
                'iduser.not_in' => 'Nhân viên này không có trong cửa hàng',
                'hour.required' => 'Vui lòng nhập số giờ làm',
                'hour.numeric' => 'Số giờ làm phải là số',
                'wddate.required' => 'Vui lòng nhập ngày chấm công',
                'wddate.min' => 'Ngày chấm công không đúng định dạng'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewWorkday_Error')->withInput();
        }

        try {
            $a = explode('/', $req->wddate);
            $wddate = Carbon::create($a[2], $a[1], $a[0]);
            $check = Workday::where('userid', $req->iduser)->where('wddate', $wddate)->first();
            if (!$check) {
                $wd = new Workday;
                $wd->userid = $req->iduser;
                $wd->wddate = $wddate;
                $wd->hour = $req->hour;
                $wd->shopid = Auth::user()->shopid;
                $wd->save();
                return redirect()->back()->with('success', '');
            } else
                return redirect()->back()->with('wderr', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditWorkday(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'houredit' => 'required|numeric',
                'wddateedit' => 'required|min:10'
            ],
            [
                'houredit.required' => 'Vui lòng nhập số giờ làm',
                'houredit.numeric' => 'Số giờ làm phải là số',
                'wddateedit.required' => 'Vui lòng nhập ngày chấm công',
                'wddateedit.min' => 'Ngày chấm công không đúng định dạng'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditWorkday_Error')->withInput();
        }

        try {
            $a = explode('/', $req->wddateedit);
            $wddate = Carbon::create($a[2], $a[1], $a[0]);
            $check = Workday::where('userid', $req->iduser)->where('wddate', $wddate)->first();
            if (!$check) {
                $wd = Workday::where('idwd', $req->idwd)->first();
                $wd->wddate = $wddate;
                $wd->hour = $req->houredit;
                $wd->update();
                return redirect()->back()->with('success', '');
            } else
                return redirect()->back()->with('wderr', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteWorkday(Request $req)
    {
        $wd = Workday::where('idwd', $req->idwddel)->first();
        $wd->delete();
        return redirect()->back()->with('succ', '');
    }

    public function getCategory(Request $req)
    {
        $procates = ProductCate::where('procatename', 'like', '%' . $req->search . '%')->where('shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('category', compact('procates', 'search'));
    }

    public function postNewCategory(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'procatename' => ['required', 'max:100', Rule::unique('productcate')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })]
            ],
            [
                'procatename.required' => 'Vui lòng nhập tên thực đơn',
                'procatename.unique' => 'Thực đơn "' . ucwords($req->procatename) . '" đã tồn tại. Vui lòng thử lại với tên khác',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewCate_Error')->withInput();
        }

        try {
            $procate = new ProductCate;
            $procate->procatename = $req->procatename;
            $procate->shopid = Auth::user()->shopid;
            $procate->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditCategory(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'procatenameedit' => ['required', 'max:100', Rule::unique('productcate', 'procatename')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->idprocate, 'idprocate')]
            ],
            [
                'procatenameedit.required' => 'Vui lòng nhập tên khu vực',
                'procatenameedit.unique' => 'Thực đơn "' . ucwords($req->procatenameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditCate_Error')->withInput();
        }

        try {
            $procate = ProductCate::where('idprocate', $req->idprocate)->first();
            $procate->procatename = $req->procatenameedit;
            $procate->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteCategory(Request $req)
    {
        $procate = ProductCate::where('idprocate', $req->idprocatedel)->first();
        $pro = Product::where('procateid', $procate->idprocate)->get();
        if ($pro->count() == 0) {
            $procate->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getMaterial(Request $req)
    {
        $materials = Material::where('maname', 'like', '%' . $req->search . '%')->where('shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        return view('material', compact('materials', 'search'));
    }

    public function postNewMaterial(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'maname' => ['required', 'max:100', Rule::unique('material')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })],
                'maprice' => 'nullable|numeric|min:0|max:100000000',
                'unit' => 'required|max:50'
            ],
            [
                'maname.required' => 'Vui lòng nhập tên nguyên liệu',
                'maname.unique' => 'Nguyên liệu "' . ucwords($req->maname) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'maprice.numeric' => 'Giá nhập phải là số',
                'maprice.min' => 'Giá nhập phải lớn hơn 0',
                'maprice.max' => 'Giá nhập phải nhỏ hơn 100.000.000',
                'unit.required' => 'Vui lòng nhập đơn vị tính'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewMaterial_Error')->withInput();
        }

        try {
            $material = new Material;
            $material->maname = $req->maname;
            if (!empty($req->maprice) || $req->maprice != '')
                $material->maprice = $req->maprice;
            $material->unit = $req->unit;
            $material->shopid = Auth::user()->shopid;
            $material->save();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postEditMaterial(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'manameedit' => ['required', 'max:100', Rule::unique('material', 'maname')->where(function ($query) {
                    return $query->where('shopid', Auth::user()->shopid);
                })->ignore($req->idma, 'idma')],
                'mapriceedit' => 'numeric|min:0|max:100000000',
                'unitedit' => 'required|max:50',
                'maamountedit' => 'required|numeric|min:0|max:100000000'
            ],
            [
                'manameedit.required' => 'Vui lòng nhập tên nguyên liệu',
                'manameedit.unique' => 'Nguyên liệu "' . ucwords($req->manameedit) . '" đã tồn tại. Vui lòng thử lại với tên khác',
                'mapriceedit.numeric' => 'Giá nhập phải là số',
                'mapriceedit.min' => 'Giá nhập phải lớn hơn 0',
                'mapriceedit.max' => 'Giá nhập phải nhỏ hơn 100.000.000',
                'unitedit.required' => 'Vui lòng nhập đơn vị tính',
                'maamountedit.min' => 'Số lượng tồn phải lớn hơn 0',
                'maamountedit.max' => 'Số lượng tồn phải nhỏ hơn 100.000.000',
                'maamountedit.required' => 'Vui lòng nhập số lượng tồn',
                'maamountedit.numeric' => 'Số lượng tồn phải là số',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewMaterial_Error')->withInput();
        }

        try {
            $material = Material::where('idma', $req->idma)->first();
            $material->maname = $req->manameedit;
            if (!empty($req->mapriceedit) || $req->mapriceedit != '')
                $material->maprice = $req->mapriceedit;
            $material->unit = $req->unitedit;
            $material->maamount = $req->maamountedit;
            $material->update();
            return redirect()->back()->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('err', '');
        }
    }

    public function postDeleteMaterial(Request $req)
    {
        $material = Material::where('idma', $req->idmadel)->first();
        $pro = ImportDetail::where('maid', $material->idma)->get();
        if ($pro->count() == 0) {
            $material->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getImport(Request $req)
    {
        $dayfrom = Carbon::today('Asia/Ho_Chi_Minh');
        $dayto = Carbon::today('Asia/Ho_Chi_Minh');
        if ($req->dayfrom && $req->dayto) {
            $a = explode('/', $req->dayfrom);
            $b = explode('/', $req->dayto);
            $dayfrom = Carbon::create($a[2], $a[1], $a[0]);
            $dayto = Carbon::create($b[2], $b[1], $b[0]);
        }
        $imps = Import::join('supplier', 'import.suppid', 'supplier.idsupp')->join('users', 'users.id', 'import.userid')
            ->where('import.shopid', Auth::user()->shopid)->where('impdate', '<=', $dayto)->where('impdate', '>=', $dayfrom)->get();
        $mas = Material::all();
        $supps = Supplier::all();
        return view('import', compact('imps', 'dayfrom', 'dayto', 'mas', 'supps'));
    }

    public function getNewImport()
    {
        $supps = Supplier::where('shopid', Auth::user()->shopid)->get();
        $mas = Material::where('shopid', Auth::user()->shopid)->get();
        return view('newimport', compact('supps', 'mas'));
    }

    public function postNewImport(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'idsupp' => 'required|numeric|not_in:supplier',
                'impdate' => 'required|date',
                'idma.*' => 'required|numeric|not_in:material',
                'impamount.*' => 'required|numeric|min:1|max:1000000',
                'impprice.*' => 'required|numeric|min:1|max:1000000000',
                'imptotal.*' => 'required|numeric',

            ],
            [
                'idsupp.required' => 'Vui lòng chọn nhà cung cấp',
                'idsupp.numeric' => 'Nhà cung cấp phải là số',
                'idsupp.not_in' => 'Nhà cung cấp không phù hợp',
                'impdate.required' => 'Vui lòng chọn ngày nhập kho',
                'impdate.date' => 'Ngày nhập kho không đúng định dạng',
                'idmma.required' => 'Vui lòng chọn nguyên liệu',
                'idma.numeric' => 'Nguyên liệu phải là số',
                'idma.not_in' => 'Nguyên liệu không phù hợp',
                'impamount.*.required' => 'Vui lòng nhập số lượng',
                'impamount.*.numeric' => 'Số lượng phải là số',
                'impamount.*.min' => 'Số lượng phải lớn hơn 0',
                'impamount.*.max' => 'Số lượng phải nhỏ hơn 1.000.000',
                'impprice.*.required' => 'Vui lòng nhập đơn giá',
                'impprice.*.numeric' => 'Đơn giá phải là số',
                'impprice.*.min' => 'Đơn giá phải lớn hơn 0',
                'impprice.*.max' => 'Đơn giá phải nhỏ hơn 1.000.000.000',
                'imptotal.*.required' => 'Vui lòng nhập thành tiền',
                'imptotal.*.numeric' => 'Thành tiền phải là số'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewImport_Error')->withInput();
        }

        try {
            $temp = str_replace('₫', '', $req->total);
            $total = str_replace(',', '', $temp);
            $a = explode('/', $req->impdate);
            $impdate = Carbon::create($a[2], $a[1], $a[0]);

            $imp = new Import;
            $imp->suppid = $req->idsupp;
            $imp->userid = Auth::user()->id;
            $imp->impdate = $impdate;
            $imp->total = $total;
            $imp->shopid = Auth::user()->shopid;
            $imp->save();

            foreach ($req->idma as $key => $value) {
                $impdetail = new ImportDetail;
                $impdetail->impid = $imp->idimp;
                $impdetail->maid = $value;
                $impdetail->impamount = $req->impamount[$key];
                $impdetail->impprice = $req->impprice[$key];
                $impdetail->imptotal = $req->imptotal[$key];
                $impdetail->save();
                $ma = Material::where('idma', $value)->first();
                $ma->maprice = ($ma->maprice + $req->impprice[$key]) / 2;
                $ma->maamount = $ma->maamount + $req->impamount[$key];
                $ma->update();
            }
            return redirect()->route('import')->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->route('new-import')->with('err', '');
        }
    }

    public function getEditImport($id)
    {
        $imp = Import::where('idimp', $id)->first();
        if ($imp) {
            $impdes = ImportDetail::join('material', 'material.idma', 'importdetail.maid')->where('impid', $id)->get();
            $supps = Supplier::where('shopid', Auth::user()->shopid)->get();
            $mas = Material::where('shopid', Auth::user()->shopid)->get();
            return view('editimport', compact('supps', 'mas', 'imp', 'impdes'));
        } else
            return redirect()->route('import');
    }

    public function postEditImport(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'idsupp' => 'required|numeric|not_in:supplier',
                'impdate' => 'required|date',
                'idma.*' => 'required|numeric|not_in:material',
                'impamount.*' => 'required|numeric|min:1|max:1000000',
                'impprice.*' => 'required|numeric|min:1|max:1000000000',
                'imptotal.*' => 'required|numeric',

            ],
            [
                'idsupp.required' => 'Vui lòng chọn nhà cung cấp',
                'idsupp.numeric' => 'Nhà cung cấp phải là số',
                'idsupp.not_in' => 'Nhà cung cấp không phù hợp',
                'impdate.required' => 'Vui lòng chọn ngày nhập kho',
                'impdate.date' => 'Ngày nhập kho không đúng định dạng',
                'idmma.required' => 'Vui lòng chọn nguyên liệu',
                'idma.numeric' => 'Nguyên liệu phải là số',
                'idma.not_in' => 'Nguyên liệu không phù hợp',
                'impamount.*.required' => 'Vui lòng nhập số lượng',
                'impamount.*.numeric' => 'Số lượng phải là số',
                'impamount.*.min' => 'Số lượng phải lớn hơn 0',
                'impamount.*.max' => 'Số lượng phải nhỏ hơn 1.000.000',
                'impprice.*.required' => 'Vui lòng nhập đơn giá',
                'impprice.*.numeric' => 'Đơn giá phải là số',
                'impprice.*.min' => 'Đơn giá phải lớn hơn 0',
                'impprice.*.max' => 'Đơn giá phải nhỏ hơn 1.000.000.000',
                'imptotal.*.required' => 'Vui lòng nhập thành tiền',
                'imptotal.*.numeric' => 'Thành tiền phải là số'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditImport_Error')->withInput();
        }

        try {
            $temp = str_replace('₫', '', $req->total);
            $total = str_replace(',', '', $temp);
            $a = explode('/', $req->impdate);
            $impdate = Carbon::create($a[2], $a[1], $a[0]);
            $size = count($req->idimpde);

            $imp = Import::where('idimp', $req->idimp)->first();
            $imp->suppid = $req->idsupp;
            $imp->userid = Auth::user()->id;
            $imp->impdate = $impdate;
            $imp->total = $total;
            $imp->shopid = Auth::user()->shopid;
            $imp->update();

            foreach ($req->idma as $key => $value) {
                if ($key < $size) {
                    $impdetail = ImportDetail::where('idimpde', $req->idimpde[$key])->first();

                    $ma = Material::where('idma', $value)->first();
                    if ($impdetail->maid == $value) {
                        $ma->maprice = $ma->maprice + ($req->impprice[$key] - $impdetail->impprice) / 2;
                        $ma->maamount = $ma->maamount - $impdetail->impamount + $req->impamount[$key];
                    } else {
                        $mad = Material::where('idma', $impdetail->maid)->first();
                        $mad->maamount = $mad->maamount - $impdetail->impamount;
                        $mad->maprice = $mad->maprice - $impdetail->impprice + $mad->maprice;
                        $mad->update();

                        $ma->maamount = $ma->maamount + $req->impamount[$key];
                        $ma->maprice = ($ma->maprice + $req->impprice[$key]) / 2;
                    }
                    $ma->update();

                    $impdetail->maid = $value;
                    $impdetail->impamount = $req->impamount[$key];
                    $impdetail->impprice = $req->impprice[$key];
                    $impdetail->imptotal = $req->imptotal[$key];
                    $impdetail->update();
                } else {
                    $impdetail = new ImportDetail;
                    $impdetail->impid = $imp->idimp;
                    $impdetail->maid = $value;
                    $impdetail->impamount = $req->impamount[$key];
                    $impdetail->impprice = $req->impprice[$key];
                    $impdetail->imptotal = $req->imptotal[$key];
                    $impdetail->save();
                    $ma = Material::where('idma', $value)->first();
                    $ma->maprice = ($ma->maprice + $req->impprice[$key]) / 2;
                    $ma->maamount = $ma->maamount + $req->impamount[$key];
                    $ma->update();
                }
            }
            return redirect()->route('import')->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->route('eidt-import', $req->idimp)->with('err', '');
        }
    }

    public function getImportDetailView(Request $req)
    {
        $data = ImportDetail::join('material', 'material.idma', 'importdetail.maid')->where('impid', $req->idimp)->get();
        return response()->json($data);
    }

    public function getImportFindPrice(Request $req)
    {
        $data = Material::select('maprice', 'unit')->where('idma', $req->idma)->first();
        return response()->json($data);
    }

    public function postDeleteImport(Request $req)
    {
        $imp = Import::where('idimp', $req->idimpdel)->first();
        try {
            $impde = ImportDetail::where('impid', $imp->idimp)->get();
            foreach ($impde as $v) {
                $ma = Material::where('idma', $v->maid)->first();
                $ma->maamount = $ma->maamount - $v->impamount;
                $ma->maprice = $ma->maprice - $v->impprice + $ma->maprice;
                $ma->update();
            }
            ImportDetail::where('impid', $imp->idimp)->delete();
            $imp->delete();
            return redirect()->back()->with('succ', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', '');
        }
    }

    public function getProduct(Request $req)
    {
        $pros = Product::join('productcate', 'productcate.idprocate', 'product.procateid')
            ->where('proname', 'like', '%' . $req->search . '%')->where('product.shopid', Auth::user()->shopid)->get();
        $search = $req->search;
        $mas = Material::all();
        $cates = ProductCate::all();
        return view('product', compact('pros', 'search', 'mas', 'cates'));
    }

    public function getNewProduct()
    {
        $procates = ProductCate::where('shopid', Auth::user()->shopid)->get();
        $mas = Material::where('shopid', Auth::user()->shopid)->get();
        return view('newproduct', compact('procates', 'mas'));
    }

    public function postNewProduct(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'idprocate' => 'required|numeric|not_in:productcate',
                'proname' => 'required|max:100',
                'proprice' => 'required|numeric|min:0|max:1000000000',
                'idma.*' => 'required|numeric|not_in:material',
                'number.*' => 'required|numeric|min:0|max:10000',
            ],
            [
                'idprocate.required' => 'Vui lòng chọn thực đơn',
                'idprocate.numeric' => 'Thực đơn phải là số',
                'idprocate.not_in' => 'Thực đơn không phù hợp',
                'proname.required' => 'Vui lòng chọn ngày nhập kho',
                'proname.max' => 'Tên thực đơn tối đa 100 ký tự',
                'idmma.required' => 'Vui lòng chọn nguyên liệu',
                'idma.numeric' => 'Nguyên liệu phải là số',
                'idma.not_in' => 'Nguyên liệu không phù hợp',
                'number.*.required' => 'Vui lòng nhập số lượng',
                'number.*.numeric' => 'Số lượng phải là số',
                'number.*.min' => 'Số lượng phải lớn hơn 0',
                'number.*.max' => 'Số lượng phải nhỏ hơn 10.000',
                'proprice.required' => 'Vui lòng nhập giá bán',
                'proprice.numeric' => 'Giá bán phải là số',
                'proprice.min' => 'Giá bán phải lớn hơn 0',
                'proprice.max' => 'Giá bán phải nhỏ hơn 1.000.000.000',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewProduct_Error')->withInput();
        }

        try {
            $arrdup = array_diff_assoc($req->idma, array_unique($req->idma));
            if (!$arrdup) {
                $pro = new Product;
                $pro->proname = $req->proname;
                $pro->proprice = $req->proprice;
                $pro->procateid = $req->idprocate;
                $pro->shopid = Auth::user()->shopid;
                $pro->save();

                foreach ($req->idma as $key => $value) {
                    $fo = new Formula;
                    $fo->proid = $pro->idpro;
                    $fo->maid = $value;
                    $fo->number = $req->number[$key];
                    $fo->save();
                }
                return redirect()->route('product')->with('success', '');
            } else
                return redirect()->route('new-product')->with('dupp', 'Mỗi nguyên liệu chỉ được nhập 1 dòng');
        } catch (\Throwable $th) {
            return redirect()->route('new-product')->with('err', '');
        }
    }

    public function getProductDetailView(Request $req)
    {
        $data = Formula::join('material', 'material.idma', 'formula.maid')->where('proid', $req->idpro)->get();
        return response()->json($data);
    }

    public function getEditProduct($id)
    {
        $pro = Product::where('idpro', $id)->first();
        if ($pro) {
            $fos = Formula::join('material', 'material.idma', 'formula.maid')->where('proid', $id)->get();
            $procates = ProductCate::where('shopid', Auth::user()->shopid)->get();
            $mas = Material::where('shopid', Auth::user()->shopid)->get();
            return view('editproduct', compact('pro', 'mas', 'fos', 'procates'));
        } else
            return redirect()->route('product');
    }

    public function postEditProduct(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'idprocate' => 'required|numeric|not_in:productcate',
                'proname' => 'required|max:100',
                'proprice' => 'required|numeric|min:0|max:1000000000',
                'idma.*' => 'required|numeric|not_in:material',
                'number.*' => 'required|numeric|min:0|max:10000',
            ],
            [
                'idprocate.required' => 'Vui lòng chọn thực đơn',
                'idprocate.numeric' => 'Thực đơn phải là số',
                'idprocate.not_in' => 'Thực đơn không phù hợp',
                'proname.required' => 'Vui lòng chọn ngày nhập kho',
                'proname.max' => 'Tên thực đơn tối đa 100 ký tự',
                'idmma.required' => 'Vui lòng chọn nguyên liệu',
                'idma.numeric' => 'Nguyên liệu phải là số',
                'idma.not_in' => 'Nguyên liệu không phù hợp',
                'number.*.required' => 'Vui lòng nhập số lượng',
                'number.*.numeric' => 'Số lượng phải là số',
                'number.*.min' => 'Số lượng phải lớn hơn 0',
                'number.*.max' => 'Số lượng phải nhỏ hơn 10.000',
                'proprice.required' => 'Vui lòng nhập giá bán',
                'proprice.numeric' => 'Giá bán phải là số',
                'proprice.min' => 'Giá bán phải lớn hơn 0',
                'proprice.max' => 'Giá bán phải nhỏ hơn 1.000.000.000',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postEditProduct_Error')->withInput();
        }

        try {
            $arrdup = array_diff_assoc($req->idma, array_unique($req->idma));
            if (!$arrdup) {
                $pro = Product::where('idpro', $req->idpro)->first();
                $pro->proname = $req->proname;
                $pro->proprice = $req->proprice;
                $pro->procateid = $req->idprocate;
                $pro->update();

                $idfo = Formula::where('proid', $pro->idpro)->pluck('idfo')->toArray();
                if ($req->idfo) {
                    $diff = array_merge(array_diff($req->idfo, $idfo), array_diff($idfo, $req->idfo));
                    foreach ($req->idma as $key => $value) {
                        if (array_key_exists($key, $req->idfo)) {
                            $fo = Formula::where('idfo', $req->idfo[$key])->first();
                            $fo->maid = $value;
                            $fo->number = $req->number[$key];
                            $fo->update();
                        } else {
                            $fo = new Formula;
                            $fo->proid = $pro->idpro;
                            $fo->maid = $value;
                            $fo->number = $req->number[$key];
                            $fo->save();
                        }
                    }
                } else {
                    $diff = $idfo;
                    foreach ($req->idma as $key => $value) {
                        $fo = new Formula;
                        $fo->proid = $pro->idpro;
                        $fo->maid = $value;
                        $fo->number = $req->number[$key];
                        $fo->save();
                    }
                }
                foreach ($diff as $d) {
                    Formula::where('idfo', $d)->delete();
                }
                return redirect()->route('product')->with('success', '');
            } else
                return redirect()->route('edit-product', $req->idpro)->with('dupp', 'Mỗi nguyên liệu chỉ được nhập 1 dòng');
        } catch (\Throwable $th) {
            return redirect()->route('edit-product', $req->idpro)->with('err', '');
        }
    }

    public function postDeleteProduct(Request $req)
    {
        try {
            $pro = Product::where('idpro', $req->idprodel)->first();
            $billde = BillDetail::where('proid', $pro->idpro)->get();
            if ($billde->count() == 0) {
                Formula::where('proid', $pro->idpro)->delete();
                $pro->delete();
                return redirect()->back()->with('succ', '');
            } else
                return redirect()->back()->with('error', '');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', '');
        }
    }

    public function getSell()
    {
        $desks = Desk::where('shopid', Auth::user()->shopid)->get();
        $zones = Zone::where('shopid', Auth::user()->shopid)->get();
        $shop = Shop::where('idshop', Auth::user()->shopid)->first();
        return view('sell', compact('desks', 'zones', 'shop'));
    }

    public function getNewCall($id)
    {
        $desk = Desk::where('iddesk', $id)->where('shopid', Auth::user()->shopid)->first();
        $cates = ProductCate::where('shopid', Auth::user()->shopid)->get();
        $pros = Product::where('shopid', Auth::user()->shopid)->get();
        $users = User::where('shopid', Auth::user()->shopid)->get();
        return view('newcall', compact('desk', 'cates', 'pros', 'users'));
    }

    public function getFindCate(Request $req)
    {
        if ($req->idprocate == 0)
            $data = Product::where('shopid', Auth::user()->shopid)->get();
        else
            $data = Product::where('procateid', $req->idprocate)->where('shopid', Auth::user()->shopid)->get();
        return response()->json($data);
    }

    public function getFindProPrice(Request $req)
    {
        $data = Product::where('idpro', $req->idpro)->where('shopid', Auth::user()->shopid)->first();
        return response()->json($data);
    }

    public function getCheck(Request $req)
    {
        $fos = Formula::where('proid', $req->proid)->get();
        $data = 0;
        foreach ($fos as $f) {
            $ma = Material::where('idma', $f->maid)->first();
            if ($ma->maamount < $f->number * $req->num) {
                $data = 1;
                break;
            }
        }
        return response()->json($data);
    }

    public function getViewMenu(Request $req)
    {
        $bill = Bill::where('deskid', $req->deskid)->where('shopid', Auth::user()->shopid)->orderBy('idbill', 'desc')->first();
        $data = BillDetail::join('product', 'product.idpro', 'billdetail.proid')->where('billid', $bill->idbill)->get();
        return response()->json($data);
    }

    public function postNewCall(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'idprocate.*' => 'required|numeric|not_in:productcate',
                'idpro.*' => 'required|numeric|not_in:product',
                'billamount.*' => 'required|numeric|min:1|max:1000',
                'billprice.*' => 'required|numeric|min:1|max:1000000000',
                'billtotal.*' => 'required|numeric|min:1|max:1000000000000',
            ],
            [
                'idprocate.*.required' => 'Vui lòng chọn thực đơn',
                'idprocate.*.numeric' => 'Thực đơn phải là số',
                'idprocate.*.not_in' => 'Thực đơn không phù hợp',
                'idpro.*.required' => 'Vui lòng chọn món',
                'idpro.*.numeric' => 'Món phải là số',
                'idpro.*.not_in' => 'Món không phù hợp',
                'billamount.*.required' => 'Vui lòng nhập số lượng',
                'billamount.*.numeric' => 'Số lượng phải là số',
                'billamount.*.min' => 'Số lượng phải lớn hơn 0',
                'billamount.*.max' => 'Số lượng phải nhỏ hơn 1.000',
                'billprice.required' => 'Vui lòng nhập giá bán',
                'billprice.numeric' => 'Giá bán phải là số',
                'billprice.min' => 'Giá bán phải lớn hơn 0',
                'billprice.max' => 'Giá bán phải nhỏ hơn 1.000.000.000',
                'billtotal.required' => 'Vui lòng nhập thành tiền',
                'billtotal.numeric' => 'Thành tiền phải là số',
                'billtotal.min' => 'Thành tiền phải lớn hơn 0',
                'billtotal.max' => 'Thành tiền phải nhỏ hơn 1.000.000.000.000',

            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewCall_Error')->withInput();
        }

        try {
            $arrdup = array_diff_assoc($req->idpro, array_unique($req->idpro));
            if (!$arrdup) {
                $temp = str_replace('₫', '', $req->total);
                $total = str_replace(',', '', $temp);
                $bill = new Bill;
                $bill->userid = $req->iduser;
                $bill->deskid = $req->deskid;
                $bill->billdate = Carbon::today('Asia/Ho_Chi_Minh');
                $bill->total = $total;
                $bill->shopid = Auth::user()->shopid;
                $bill->save();
                Desk::where('iddesk', $req->deskid)->update(['state' => 1]);

                foreach ($req->idprocate as $key => $value) {
                    $billde = new BillDetail;
                    $billde->billid = $bill->idbill;
                    $billde->procateid = $value;
                    $billde->proid = $req->idpro[$key];
                    $billde->billamount = $req->billamount[$key];
                    $billde->billprice = $req->billprice[$key];
                    $billde->billtotal = $req->billtotal[$key];
                    $billde->save();

                    $fos = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $req->idpro[$key])->where('shopid', Auth::user()->shopid)->get();
                    foreach ($fos as $f) {
                        $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                        $ma->maamount = $ma->maamount - $f->number * $req->billamount[$key];
                        $ma->update();
                    }
                }
                return redirect()->route('sell')->with('success', '');
            } else
                return redirect()->route('new-call', $req->deskid)->with('dupp', 'Mỗi món chỉ được nhập 1 dòng');
        } catch (\Throwable $th) {
            return redirect()->route('new-call', $req->deskid)->with('err', '');
        }
    }

    public function getEditCall($id)
    {
        $desk = Desk::where('iddesk', $id)->where('shopid', Auth::user()->shopid)->first();
        $cates = ProductCate::where('shopid', Auth::user()->shopid)->get();
        $pros = Product::where('shopid', Auth::user()->shopid)->get();
        $users = User::where('shopid', Auth::user()->shopid)->get();
        $bill = Bill::where('deskid', $desk->iddesk)->where('shopid', Auth::user()->shopid)->orderBy('idbill', 'desc')->first();
        $billde = BillDetail::join('product', 'product.idpro', 'billdetail.proid')->where('billid', $bill->idbill)->get();
        return view('editcall', compact('desk', 'cates', 'pros', 'users', 'bill', 'billde'));
    }

    public function postEditCall(Request $req)
    {
        $validator = Validator::make(
            $req->all(),
            [
                'idprocate.*' => 'required|numeric|not_in:productcate',
                'idpro.*' => 'required|numeric|not_in:product',
                'billamount.*' => 'required|numeric|min:1|max:1000',
                'billprice.*' => 'required|numeric|min:1|max:1000000000',
                'billtotal.*' => 'required|numeric|min:1|max:1000000000000',
            ],
            [
                'idprocate.*.required' => 'Vui lòng chọn thực đơn',
                'idprocate.*.numeric' => 'Thực đơn phải là số',
                'idprocate.*.not_in' => 'Thực đơn không phù hợp',
                'idpro.*.required' => 'Vui lòng chọn món',
                'idpro.*.numeric' => 'Món phải là số',
                'idpro.*.not_in' => 'Món không phù hợp',
                'billamount.*.required' => 'Vui lòng nhập số lượng',
                'billamount.*.numeric' => 'Số lượng phải là số',
                'billamount.*.min' => 'Số lượng phải lớn hơn 0',
                'billamount.*.max' => 'Số lượng phải nhỏ hơn 1.000',
                'billprice.required' => 'Vui lòng nhập giá bán',
                'billprice.numeric' => 'Giá bán phải là số',
                'billprice.min' => 'Giá bán phải lớn hơn 0',
                'billprice.max' => 'Giá bán phải nhỏ hơn 1.000.000.000',
                'billtotal.required' => 'Vui lòng nhập thành tiền',
                'billtotal.numeric' => 'Thành tiền phải là số',
                'billtotal.min' => 'Thành tiền phải lớn hơn 0',
                'billtotal.max' => 'Thành tiền phải nhỏ hơn 1.000.000.000.000',

            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'postNewCall_Error')->withInput();
        }

        try {
            $arrdup = array_diff_assoc($req->idpro, array_unique($req->idpro));
            if (!$arrdup) {
                $temp = str_replace('₫', '', $req->total);
                $total = str_replace(',', '', $temp);
                $bill = Bill::where('idbill', $req->idbill)->first();
                $bill->userid = $req->iduser;
                $bill->total = $total;
                $bill->update();

                $idbillde = BillDetail::where('billid', $bill->idbill)->pluck('idbillde')->toArray();
                if ($req->idbillde) {
                    $diff = array_merge(array_diff($req->idbillde, $idbillde), array_diff($idbillde, $req->idbillde));
                    foreach ($req->idprocate as $key => $value) {
                        if (array_key_exists($key, $req->idbillde)) {
                            $bde = BillDetail::where('idbillde', $req->idbillde[$key])->first();

                            $old = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $bde->proid)->where('shopid', Auth::user()->shopid)->get();
                            foreach ($old as $f) {
                                $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                                $ma->maamount = $ma->maamount + $f->number * $bde->billamount;
                                $ma->update();
                            }

                            $new = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $req->idpro[$key])->where('shopid', Auth::user()->shopid)->get();
                            foreach ($new as $f) {
                                $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                                $ma->maamount = $ma->maamount - $f->number * $req->billamount[$key];
                                $ma->update();
                            }

                            $bde->procateid = $value;
                            $bde->proid = $req->idpro[$key];
                            $bde->billamount = $req->billamount[$key];
                            $bde->billprice = $req->billprice[$key];
                            $bde->billtotal = $req->billtotal[$key];
                            $bde->update();
                        } else {
                            $billde = new BillDetail;
                            $billde->billid = $bill->idbill;
                            $billde->procateid = $value;
                            $billde->proid = $req->idpro[$key];
                            $billde->billamount = $req->billamount[$key];
                            $billde->billprice = $req->billprice[$key];
                            $billde->billtotal = $req->billtotal[$key];
                            $billde->save();

                            $new = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $req->idpro[$key])->where('shopid', Auth::user()->shopid)->get();
                            foreach ($new as $f) {
                                $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                                $ma->maamount = $ma->maamount - $f->number * $req->billamount[$key];
                                $ma->update();
                            }
                        }
                    }
                } else {
                    $diff = $idbillde;
                    foreach ($req->idprocate as $key => $value) {
                        $billde = new BillDetail;
                        $billde->billid = $bill->idbill;
                        $billde->procateid = $value;
                        $billde->proid = $req->idpro[$key];
                        $billde->billamount = $req->billamount[$key];
                        $billde->billprice = $req->billprice[$key];
                        $billde->billtotal = $req->billtotal[$key];
                        $billde->save();

                        $fos = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $req->idpro[$key])->where('shopid', Auth::user()->shopid)->get();
                        foreach ($fos as $f) {
                            $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                            $ma->maamount = $ma->maamount - $f->number * $req->billamount[$key];
                            $ma->update();
                        }
                    }
                }
                foreach ($diff as $d) {
                    $bde = BillDetail::where('idbillde', $d)->first();
                    $old = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $bde->proid)->where('shopid', Auth::user()->shopid)->get();
                    foreach ($old as $f) {
                        $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                        $ma->maamount = $ma->maamount + $f->number * $bde->billamount;
                        $ma->update();
                    }
                    $bde->delete();
                }
                return redirect()->route('sell')->with('success', '');
            } else
                return redirect()->route('edit-call', $req->deskid)->with('dupp', 'Mỗi món chỉ được nhập 1 dòng');
        } catch (\Throwable $th) {
            return redirect()->route('edit-call', $req->deskid)->with('err', '');
        }
    }

    public function postDeleteCall(Request $req)
    {
        $bill = Bill::where('idbill', $req->idbilldel)->first();
        $billde = BillDetail::where('billid', $bill->idbill)->get();
        foreach ($billde as $d) {
            $fos = Formula::join('product', 'product.idpro', 'formula.proid')->where('proid', $d->proid)->where('shopid', Auth::user()->shopid)->get();
            foreach ($fos as $f) {
                $ma = Material::where('idma', $f->maid)->where('shopid', Auth::user()->shopid)->first();
                $ma->maamount = $ma->maamount + $f->number * $d->billamount;
                $ma->update();
            }
            $d->delete();
        }
        $bill->delete();
        Desk::where('iddesk', $req->iddeskdel)->update(['state' => 0]);
        return redirect()->route('sell')->with('success', '');
    }

    public function getCheckEdit(Request $req)
    {
        $data = 0;
        if ($req->idbillde) {
            $billde = BillDetail::where('idbillde', $req->idbillde)->first();
            $old = Formula::where('proid', $billde->proid)->get();
            foreach ($old as $f) {
                $ma = Material::where('idma', $f->maid)->first();
                $ma->maamount = $ma->maamount + $f->number * $billde->billamount;
                $new = Formula::where('proid', $req->proid)->get();
                foreach ($new as $fn) {
                    if ($ma->maamount < $fn->number * $req->num) {
                        $data = 1;
                        break;
                    }
                }
            }
        } else {
            $fos = Formula::where('proid', $req->proid)->get();
            foreach ($fos as $f) {
                $ma = Material::where('idma', $f->maid)->first();
                if ($ma->maamount < $f->number * $req->num) {
                    $data = 1;
                    break;
                }
            }
        }
        return response()->json($data);
    }

    public function postMerge(Request $req)
    {
        try {
            $old = Bill::where('deskid', $req->olddesk)->where('shopid', Auth::user()->shopid)->first();
            $new = Bill::where('deskid', $req->newdesk)->where('shopid', Auth::user()->shopid)->first();
            $bd = BillDetail::where('billid', $new->idbill)->get();
            foreach ($bd as $b) {
                $o = BillDetail::where('billid', $old->idbill)->where('proid', $b->proid)->first();
                if ($o) {
                    $o->billamount = $o->billamount + $b->billamount;
                    $o->billprice = ($o->billprice + $b->billprice) / 2;
                    $o->billtotal = $o->billtotal + $b->billtotal;
                    $o->update();
                    $b->delete();
                } else {
                    $b->billid = $old->idbill;
                    $b->update();
                }
            }
            Desk::where('iddesk', $new->deskid)->where('shopid', Auth::user()->shopid)->update(['state' => 0]);
            $old->total = $old->total + $new->total;
            $old->update();
            $new->delete();
            return redirect()->route('sell')->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->route('sell')->with('err', '');
        }
    }

    public function getFindDesk(Request $req)
    {
        $data = Desk::where('iddesk', '<>', $req->deskid)->where('shopid', Auth::user()->shopid)->where('state', 1)->get();
        return response()->json($data);
    }

    public function getPay($id)
    {
        $today = Carbon::today('Asia/Ho_Chi_Minh');
        $desk = Desk::where('iddesk', $id)->where('shopid', Auth::user()->shopid)->first();
        $bill = Bill::where('deskid', $desk->iddesk)->where('shopid', Auth::user()->shopid)->orderBy('idbill', 'desc')->first();
        $billde = BillDetail::join('product', 'product.idpro', 'billdetail.proid')->where('billid', $bill->idbill)->get();
        $vouchers = Voucher::where('startday', '<=', $today)->where('endday', '>=', $today)->where('shopid', Auth::user()->shopid)->get();
        return view('pay', compact('desk', 'bill', 'billde', 'vouchers'));
    }

    public function getPrint($deskid, $voucherid)
    {
        $desk = Desk::where('iddesk', $deskid)->where('shopid', Auth::user()->shopid)->first();
        $bill = Bill::where('deskid', $desk->iddesk)->where('shopid', Auth::user()->shopid)->orderBy('idbill', 'desc')->first();
        $billde = BillDetail::join('product', 'product.idpro', 'billdetail.proid')->where('billid', $bill->idbill)->get();
        $shop = Shop::where('idshop', Auth::user()->shopid)->first();
        if ($voucherid == 0) {
            $voucher = new stdClass;
            $voucher->sale = 0;
        } else
            $voucher = Voucher::where('idvoucher', $voucherid)->where('shopid', Auth::user()->shopid)->first();
        return view('print', compact('desk', 'bill', 'billde', 'voucher', 'shop'));
    }

    public function getFindVoucher(Request $req)
    {
        $data = Voucher::where('idvoucher', $req->idvoucher)->where('shopid', Auth::user()->shopid)->first();
        return response()->json($data);
    }

    public function postPay(Request $req)
    {
        try {
            $temp = str_replace('₫', '', $req->total);
            $total = str_replace(',', '', $temp);
            $bill = Bill::where('deskid', $req->deskid)->where('shopid', Auth::user()->shopid)->orderBy('idbill', 'desc')->first();
            $bill->total = $total;
            $bill->pay = 1;
            $bill->voucherid = $req->idvoucher;
            if ($req->idvoucher == 0)
                $bill->billsale = 0;
            else {
                $voucher = Voucher::where('idvoucher', $req->idvoucher)->where('shopid', Auth::user()->shopid)->first();
                $bill->billsale = $voucher->sale;
            }
            $bill->update();
            Desk::where('iddesk', $req->deskid)->where('shopid', Auth::user()->shopid)->update(['state' => 0]);
            return redirect()->route('sell')->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->route('pay', $req->deskid)->with('err', '');
        }
    }

    public function getRole($id)
    {
        $pos = Position::where('idpos', $id)->where('shopid', Auth::user()->shopid)->first();
        return view('role', compact('pos'));
    }

    public function postRole(Request $req, $id)
    {
        try {
            $pos = Position::where('idpos', $id)->where('shopid', Auth::user()->shopid)->first();
            $perarr = ['position', 'employee', 'zone', 'desk', 'supplier', 'material', 'import', 'productcate', 'product', 'voucher', 'workday'];
            $permission = [];
            foreach ($perarr as $key => $value) {
                if (!empty($req->view[$key]))
                    $permission[$value . '.view'] = true;
                else
                    $permission[$value . '.view'] = false;
                if (!empty($req->create[$key]))
                    $permission[$value . '.create'] = true;
                else
                    $permission[$value . '.create'] = false;
                if (!empty($req->update[$key]))
                    $permission[$value . '.update'] = true;
                else
                    $permission[$value . '.update'] = false;
                if (!empty($req->delete[$key]))
                    $permission[$value . '.delete'] = true;
                else
                    $permission[$value . '.delete'] = false;
            }
            if (!empty($req->role))
                $permission['position.role'] = true;
            else
                $permission['position.role'] = false;

            if (!empty($req->sell_view))
                $permission['sell.view'] = true;
            else
                $permission['sell.view'] = false;

            if (!empty($req->sell_create))
                $permission['sell.create'] = true;
            else
                $permission['sell.create'] = false;

            if (!empty($req->sell_delete))
                $permission['sell.delete'] = true;
            else
                $permission['sell.delete'] = false;

            if (!empty($req->sell_merge))
                $permission['sell.merge'] = true;
            else
                $permission['sell.merge'] = false;

            if (!empty($req->sell_pay))
                $permission['sell.pay'] = true;
            else
                $permission['sell.pay'] = false;

            $pos->permissions = $permission;
            $pos->update();
            return redirect()->route('position')->with('success', '');
        } catch (\Throwable $th) {
            return redirect()->route('position')->with('err', '');
        }
    }
}
