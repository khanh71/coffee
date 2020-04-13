<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('login', 'Controller@getLogin')->name('login');
Route::post('login', 'Controller@postLogin')->name('login');
Route::get('register', 'Controller@getRegister')->name('register');
Route::post('register', 'Controller@postRegister')->name('register');
Route::group(['middleware' => 'CheckLogin'], function () {
    Route::get('logout', 'Controller@getLogout')->name('logout');
    Route::get('/', 'Controller@getDashboard')->name('/');

    //manager sell
    Route::get('sell', 'Controller@getSell')->name('sell')->middleware('can:sell.view');
    Route::get('new/table/{id}', 'Controller@getNewCall')->name('new-call')->middleware('can:sell.create');
    Route::post('new/table/{id}', 'Controller@postNewCall')->name('new-call')->middleware('can:sell.create');
    Route::get('edit/table/{id}', 'Controller@getEditCall')->name('edit-call')->middleware('can:sell.create');
    Route::post('edit/table/{id}', 'Controller@postEditCall')->name('edit-call')->middleware('can:sell.create');
    Route::post('delete-call', 'Controller@postDeleteCall')->name('delete-call')->middleware('can:sell.delete');
    Route::post('merge', 'Controller@postMerge')->name('merge')->middleware('can:sell.merge');
    Route::get('pay/{id}', 'Controller@getPay')->name('pay')->middleware('can:sell.pay');
    Route::post('pay/{id}', 'Controller@postPay')->name('pay')->middleware('can:sell.pay');
    Route::get('print/{deskid}/{voucherid}', 'Controller@getPrint')->name('print')->middleware('can:sell.pay');
    Route::get('find-voucher', 'Controller@getFindVoucher')->name('find-voucher');
    Route::get('find-desk', 'Controller@getFindDesk')->name('find-desk');
    Route::get('find-cate', 'Controller@getFindCate')->name('find-cate');
    Route::get('find-pro-price', 'Controller@getFindProPrice')->name('find-pro-price');
    Route::get('check', 'Controller@getCheck')->name('check');
    Route::get('check-edit', 'Controller@getCheckEdit')->name('check-edit');
    Route::get('view-menu', 'Controller@getViewMenu')->name('view-menu');

    //manager employee
    Route::get('employee', 'Controller@getEmployee')->name('employee')->middleware('can:employee.view');
    Route::post('employee', 'Controller@getEmployee')->name('employee')->middleware('can:employee.view');
    Route::post('new-employee', 'Controller@postNewEmployee')->name('new-employee')->middleware('can:employee.create');
    Route::post('edit-employee', 'Controller@postEditEmployee')->name('edit-employee')->middleware('can:employee.update');
    Route::post('delete-employee', 'Controller@postDeleteEmployee')->name('delete-employee')->middleware('can:employee.delete');

    //manager position
    Route::get('position', 'Controller@getPosition')->name('position')->middleware('can:position.view');
    Route::post('position', 'Controller@getPosition')->name('position')->middleware('can:position.view');
    Route::post('new-position', 'Controller@postNewPosition')->name('new-position')->middleware('can:position.create');
    Route::post('edit-position', 'Controller@postEditPosition')->name('edit-position')->middleware('can:position.update');
    Route::post('delete-position', 'Controller@postDeletePosition')->name('delete-position')->middleware('can:position.delete');
    Route::get('role/{id}', 'Controller@getRole')->name('role')->middleware('can:position.role');
    Route::post('role/{id}', 'Controller@postRole')->name('role')->middleware('can:position.role');

    //manager zone
    Route::get('zone', 'Controller@getZone')->name('zone')->middleware('can:zone.view');
    Route::post('zone', 'Controller@getZone')->name('zone')->middleware('can:zone.view');
    Route::post('new-zone', 'Controller@postNewZone')->name('new-zone')->middleware('can:zone.create');
    Route::post('edit-zone', 'Controller@postEditZone')->name('edit-zone')->middleware('can:zone.update');
    Route::post('delete-zone', 'Controller@postDeleteZone')->name('delete-zone')->middleware('can:zone.delete');

    //manager desk
    Route::get('desk', 'Controller@getDesk')->name('desk')->middleware('can:desk.view');
    Route::post('desk', 'Controller@getDesk')->name('desk')->middleware('can:desk.view');
    Route::post('new-desk', 'Controller@postNewDesk')->name('new-desk')->middleware('can:desk.create');
    Route::post('edit-desk', 'Controller@postEditDesk')->name('edit-desk')->middleware('can:desk.update');
    Route::post('delete-desk', 'Controller@postDeleteDesk')->name('delete-desk')->middleware('can:desk.delete');

    //manager voucher
    Route::get('voucher', 'Controller@getVoucher')->name('voucher')->middleware('can:voucher.view');
    Route::post('voucher', 'Controller@getVoucher')->name('voucher')->middleware('can:voucher.view');
    Route::post('new-voucher', 'Controller@postNewVoucher')->name('new-voucher')->middleware('can:voucher.create');
    Route::post('edit-voucher', 'Controller@postEditVoucher')->name('edit-voucher')->middleware('can:voucher.update');
    Route::post('delete-voucher', 'Controller@postDeleteVoucher')->name('delete-voucher')->middleware('can:voucher.delete');

    //manager supplier
    Route::get('supplier', 'Controller@getSupplier')->name('supplier')->middleware('can:supplier.view');
    Route::post('supplier', 'Controller@getSupplier')->name('supplier')->middleware('can:supplier.view');
    Route::post('new-supplier', 'Controller@postNewSupplier')->name('new-supplier')->middleware('can:supplier.create');
    Route::post('edit-supplier', 'Controller@postEditSupplier')->name('edit-supplier')->middleware('can:supplier.update');
    Route::post('delete-supplier', 'Controller@postDeleteSupplier')->name('delete-supplier')->middleware('can:supplier.delete');

    //manager workday
    Route::get('workday', 'Controller@getWorkday')->name('workday')->middleware('can:workday.view');
    Route::post('workday', 'Controller@getWorkday')->name('workday')->middleware('can:workday.view');
    Route::post('new-workday', 'Controller@postNewWorkday')->name('new-workday')->middleware('can:workday.create');
    Route::post('edit-workday', 'Controller@postEditWorkday')->name('edit-workday')->middleware('can:workday.update');
    Route::post('delete-workday', 'Controller@postDeleteWorkday')->name('delete-workday')->middleware('can:workday.delete');

    //manager product category
    Route::get('category', 'Controller@getCategory')->name('category')->middleware('can:productcate.view');
    Route::post('category', 'Controller@getCategory')->name('category')->middleware('can:productcate.view');
    Route::post('new-category', 'Controller@postNewCategory')->name('new-category')->middleware('can:productcate.create');
    Route::post('edit-category', 'Controller@postEditCategory')->name('edit-category')->middleware('can:productcate.update');
    Route::post('delete-category', 'Controller@postDeleteCategory')->name('delete-category')->middleware('can:productcate.delete');

    //manager material
    Route::get('material', 'Controller@getMaterial')->name('material')->middleware('can:material.view');
    Route::post('material', 'Controller@getMaterial')->name('material')->middleware('can:material.view');
    Route::post('new-material', 'Controller@postNewMaterial')->name('new-material')->middleware('can:material.create');
    Route::post('edit-material', 'Controller@postEditMaterial')->name('edit-material')->middleware('can:material.update');
    Route::post('delete-material', 'Controller@postDeleteMaterial')->name('delete-material')->middleware('can:material.delete');

    //manager import material
    Route::get('import', 'Controller@getImport')->name('import')->middleware('can:import.view');
    Route::post('import', 'Controller@getImport')->name('import')->middleware('can:import.view');
    Route::get('new-import', 'Controller@getNewImport')->name('new-import')->middleware('can:import.create');
    Route::post('new-import', 'Controller@postNewImport')->name('new-import')->middleware('can:import.create');
    Route::get('edit-import/{id}', 'Controller@getEditImport')->name('edit-import')->middleware('can:import.update');
    Route::post('edit-import/{id}', 'Controller@postEditImport')->name('edit-import')->middleware('can:import.update');
    Route::get('import-find-price', 'Controller@getImportFindPrice')->name('import-find-price');
    Route::get('import-detail-view', 'Controller@getImportDetailView')->name('import-detail-view')->middleware('can:import.view');
    Route::post('delete-import', 'Controller@postDeleteImport')->name('delete-import')->middleware('can:import.delete');

    //manager product
    Route::get('product', 'Controller@getProduct')->name('product')->middleware('can:product.view');
    Route::post('product', 'Controller@getProduct')->name('product')->middleware('can:product.view');
    Route::get('new-product', 'Controller@getNewProduct')->name('new-product')->middleware('can:product.create');
    Route::post('new-product', 'Controller@postNewProduct')->name('new-product')->middleware('can:product.create');
    Route::get('product-detail-view', 'Controller@getProductDetailView')->name('product-detail-view')->middleware('can:product.view');
    Route::get('edit-product/{id}', 'Controller@getEditProduct')->name('edit-product')->middleware('can:product.update');
    Route::post('edit-product/{id}', 'Controller@postEditProduct')->name('edit-product')->middleware('can:product.update');
    Route::post('delete-product', 'Controller@postDeleteProduct')->name('delete-product')->middleware('can:product.delete');
});
