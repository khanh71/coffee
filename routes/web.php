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
    Route::get('/', function () {
        return view('dashboard');
    })->name('/');
    Route::get('logout', 'Controller@getLogout')->name('logout');

    Route::get('employee', 'Controller@getEmployee')->name('employee');
    Route::post('employee', 'Controller@getEmployee')->name('employee');
    Route::post('new-employee', 'Controller@postNewEmployee')->name('new-employee');
    Route::post('edit-employee', 'Controller@postEditEmployee')->name('edit-employee');
    Route::post('delete-employee', 'Controller@postDeleteEmployee')->name('delete-employee');

    Route::get('position', 'Controller@getPosition')->name('position');
    Route::post('position', 'Controller@getPosition')->name('position');
    Route::post('new-position', 'Controller@postNewPosition')->name('new-position');
    Route::post('edit-position', 'Controller@postEditPosition')->name('edit-position');
    Route::post('delete-position', 'Controller@postDeletePosition')->name('delete-position');

    Route::get('zone', 'Controller@getZone')->name('zone');
    Route::post('zone', 'Controller@getZone')->name('zone');
    Route::post('new-zone', 'Controller@postNewZone')->name('new-zone');
    Route::post('edit-zone', 'Controller@postEditZone')->name('edit-zone');
    Route::post('delete-zone', 'Controller@postDeleteZone')->name('delete-zone');

    Route::get('desk', 'Controller@getDesk')->name('desk');
    Route::post('desk', 'Controller@getDesk')->name('desk');
    Route::post('new-desk', 'Controller@postNewDesk')->name('new-desk');
    Route::post('edit-desk', 'Controller@postEditDesk')->name('edit-desk');
    Route::post('delete-desk', 'Controller@postDeleteDesk')->name('delete-desk');

    Route::get('voucher', 'Controller@getVoucher')->name('voucher');
    Route::post('voucher', 'Controller@getVoucher')->name('voucher');
    Route::post('new-voucher', 'Controller@postNewVoucher')->name('new-voucher');
    Route::post('edit-voucher', 'Controller@postEditVoucher')->name('edit-voucher');
    Route::post('delete-voucher', 'Controller@postDeleteVoucher')->name('delete-voucher');

    Route::get('supplier', 'Controller@getSupplier')->name('supplier');
    Route::post('supplier', 'Controller@getSupplier')->name('supplier');
    Route::post('new-supplier', 'Controller@postNewSupplier')->name('new-supplier');
    Route::post('edit-supplier', 'Controller@postEditSupplier')->name('edit-supplier');
    Route::post('delete-supplier', 'Controller@postDeleteSupplier')->name('delete-supplier');

    Route::get('workday', 'Controller@getWorkday')->name('workday');
    Route::post('workday', 'Controller@getWorkday')->name('workday');
    Route::post('new-workday', 'Controller@postNewWorkday')->name('new-workday');
    Route::post('edit-workday', 'Controller@postEditWorkday')->name('edit-workday');
    Route::post('delete-workday', 'Controller@postDeleteWorkday')->name('delete-workday');

    Route::get('category', 'Controller@getCategory')->name('category');
    Route::post('category', 'Controller@getCategory')->name('category');
    Route::post('new-category', 'Controller@postNewCategory')->name('new-category');
    Route::post('edit-category', 'Controller@postEditCategory')->name('edit-category');
    Route::post('delete-category', 'Controller@postDeleteCategory')->name('delete-category');

    Route::get('material', 'Controller@getMaterial')->name('material');
    Route::post('material', 'Controller@getMaterial')->name('material');
    Route::post('new-material', 'Controller@postNewMaterial')->name('new-material');
    Route::post('edit-material', 'Controller@postEditMaterial')->name('edit-material');
    Route::post('delete-material', 'Controller@postDeleteMaterial')->name('delete-material');

    Route::get('import', 'Controller@getImport')->name('import');
    Route::post('import', 'Controller@getImport')->name('import');
    Route::get('new-import', 'Controller@getNewImport')->name('new-import');
    Route::post('new-import', 'Controller@postNewImport')->name('new-import');
    Route::get('edit-import/{id}', 'Controller@getEditImport')->name('edit-import');
    Route::post('edit-import/{id}', 'Controller@postEditImport')->name('edit-import');
    Route::get('import-find-price', 'Controller@getImportFindPrice')->name('import-find-price');
    Route::get('import-detail-view', 'Controller@getImportDetailView')->name('import-detail-view');
    Route::post('delete-import', 'Controller@postDeleteImport')->name('delete-import');

    Route::get('product', 'Controller@getProduct')->name('product');
    Route::post('product', 'Controller@getProduct')->name('product');
    Route::get('new-product', 'Controller@getNewProduct')->name('new-product');
    Route::post('new-product', 'Controller@postNewProduct')->name('new-product');
    Route::get('product-detail-view', 'Controller@getProductDetailView')->name('product-detail-view');
    Route::get('edit-product/{id}', 'Controller@getEditProduct')->name('edit-product');
    Route::post('edit-product/{id}', 'Controller@postEditProduct')->name('edit-product');
    Route::post('delete-product', 'Controller@postDeleteProduct')->name('delete-product');
});
