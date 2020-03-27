<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Shop;
use App\Position;
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
            'email.unique' => 'Tên đăng nhập "' . $req->email . '" đã được đăng ký.',
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
            $user->idposition = 1;
            $user->idshop = Shop::latest('idshop')->first()->idshop;
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
        $positions = Position::where('posname', 'like', '%' . $req->search . '%')->get();
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
                'posname.required' => 'Vui lòng nhập tên danh mục',
                'posname.unique' => 'Chức vụ "' . $req->posname . '" đã tồn tại. Vui lòng thử lại với tên khác',
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
            $pos->idshop = Auth::user()->idshop;
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
                'posnameedit.required' => 'Vui lòng nhập tên danh mục',
                'posnameedit.unique' => 'Chức vụ "' . $req->posnameedit . '" đã tồn tại. Vui lòng thử lại với tên khác',
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
        $pos = Position::where('idposdel', $req->idpos)->first();
        $user = User::where('idposition', $pos->idpos)->get();
        if ($user->count() == 0) {
            $pos->delete();
            return redirect()->back()->with('succ', '');
        } else return redirect()->back()->with('error', '');
    }

    public function getEmployee(Request $req)
    {
        $employees = User::join('position', 'position.idpos', '=', 'users.idposition')
            ->where('users.idshop', Auth::user()->idshop)->where('name', 'like', '%' . $req->search . '%')->paginate(30);
        $positions = Position::where('idshop', Auth::user()->idshop)->get();

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
                'email.unique' => 'Tên đăng nhập "' . $req->email . '" đã được đăng ký',
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
            $user->idposition = $req->idpos;
            $user->idshop = Auth::user()->idshop;
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
            $user->idposition = $req->idposedit;
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
}
