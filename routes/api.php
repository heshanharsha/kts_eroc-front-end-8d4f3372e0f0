<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => '/v1', 'namespace' => 'API\v1'], function () {
    Route::post('eroc/login', 'Auth\LoginController@authenticate')->name('auth.login'); //User Login
    Route::post('eroc/register', 'Auth\RegisterController@register')->name('auth.register'); //User Register
    Route::post('eroc/refresh', 'Auth\LoginController@refresh')->name('auth.refresh'); //User refresh token
    Route::put('eroc/user/verification', 'Auth\RegisterController@verifyAccount')->name('auth.verifyAccount');
    Route::get('eroc/user/exists', 'Auth\RegisterController@checkExisitsEmail');
    Route::get('eroc/request/link/{email}', 'Auth\RegisterController@requestLink');

    // Route::middleware('auth:api')->group(function () {
    Route::get('eroc/set/Company', 'General\GeneralController@setCompany');

    Route::post('eroc/name/search', 'Search\SEOController@showName')->name('search');
   
    Route::post('eroc/name/search/society', 'Search\SEOController@showNameSociety')->name('search'); //sahani / society


        // Name onResavation
    Route::post('eroc/name/receive', 'Search\ReservationController@setName');
    Route::get('eroc/name/fix/has', 'Search\ReservationController@isGetfix');
    Route::post('eroc/name/receive/files/upload', 'Search\ReservationController@uploadFile');
        
    // Dashboard
    Route::get('eroc/name/search', 'Search\ReservationController@getSearchResult');
    Route::get('eroc/name/reservation/data', 'Search\ReservationController@getNameReservationData');
    Route::post('eroc/name/received', 'Search\ReservationController@getUserData');
    Route::put('eroc/name/payment', 'Search\ReservationController@setPayment');
    Route::put('eroc/name/re/submit', 'Search\ReservationController@setNameReSubmit');


    Route::post('eroc/users', 'Auth\UserController@getUser');
    Route::get('eroc/logout', 'Auth\LoginController@logout'); //system logout

    // General Setting 
    Route::post('eroc/status/count', 'General\GeneralController@getStatusCount');
    Route::get('eroc/company/type', 'General\GeneralController@getCompanyType');
    Route::get('eroc/company/sub/category', 'General\GeneralController@getSubCompanyType');
    Route::get('eroc/member/title', 'General\GeneralController@getMemberTitle');
    Route::post('eroc/document/feild', 'General\GeneralController@getdocDynamic');

    // Documnet Download
    Route::post('eroc/document/download', 'General\GeneralController@getDocument')->name('download-document');
    Route::delete('eroc/document/destroy', 'General\GeneralController@isFileDestroy')->name('file-destroy');
    Route::delete('eroc/resubmit/document/destroy', 'General\GeneralController@isResubmitFileDestroy')->name('ReSubmit-destroy');

    // Udara Madushan --------------------- Start --------------------------------
    Route::get('find/{token}', 'API\v1\Auth\PasswordResetController@find');
    Route::post('create', 'API\v1\Auth\PasswordResetController@create');
    Route::put('reset', 'API\v1\Auth\PasswordResetController@reset');
    // Udara Madushan --------------------- End ----------------------------------

    // Change Password
    Route::get('eroc/changePassword','Auth\ResetChangePassword@isCheckOldPassword');
    Route::put('eroc/changePassword','Auth\ResetChangePassword@changePassword')->name('changePassword');

    /* ---------------------- Udara Madushan -------------------------*/
    Route::post('/company-incorporation-data', 'Incorporation\IncorporationController@loadData')->name('incorporation-data');
    Route::post('/company-incorporation-data-step1', 'Incorporation\IncorporationController@submitStep1')->name('company-incorporation-data-step1');
    Route::post('/company-incorporation-data-step2', 'Incorporation\IncorporationController@submitStep2')->name('company-incorporation-data-step2');
    Route::post('/company-incorporation-check-nic', 'Incorporation\IncorporationController@checkNic');
    Route::post('/company-incorporation-delete-stakeholder', 'Incorporation\IncorporationController@removeStakeHolder');
    Route::post('/generate-pdf', 'Incorporation\IncorporationController@generatePDF');
    Route::post('/file-upload', 'Incorporation\IncorporationController@upload');
    Route::post('/file-remove', 'Incorporation\IncorporationController@removeDoc');
    
    Route::post('/pay', 'Incorporation\IncorporationController@submitPay');
    Route::post('/re-submit', 'Incorporation\IncorporationController@resubmit');

    Route::post('/remove-director-sec-position', 'Incorporation\IncorporationController@removeSecForDirector');
    Route::post('/remove-director-sh-position', 'Incorporation\IncorporationController@removeShForDirector');
    Route::post('/remove-sec-sh-position', 'Incorporation\IncorporationController@removeShForSec');
    /* ---------------------- Udara Madushan -------------------------*/
    // });


    /* ---------------------- ravihansa -------------------------*/
    Route::post('/secretary-data-submit', 'Secretary\SecretaryController@saveSecretaryData')->name('secretary-data-submit');
    Route::post('/secretary-data-load', 'Secretary\SecretaryController@loadSecretaryData')->name('secretary-data-load');
    Route::post('/secretary-firm-data-submit', 'Secretary\SecretaryController@saveSecretaryFirmData')->name('secretary-firm-data-submit');
    Route::post('/secretary-pvt-data-submit', 'Secretary\SecretaryController@saveSecretaryPvtData')->name(' secretary-pvt-data-submit');
    Route::post('/secretary-firm-data-load', 'Secretary\SecretaryController@loadSecretaryFirmPartnerData')->name('secretary-firm-data-load');
    Route::post('/secretary-natural-upload-pdf', 'Secretary\SecretaryController@secretaryIndividualUploadPdf')->name('secretary-natural-upload-pdf');
    Route::post('/secretary-natural-delete-pdf', 'Secretary\SecretaryController@deleteSecretaryIndividualPdf')->name('secretary-natural-delete-pdf');
    Route::post('/secretary-view-document', 'Secretary\SecretaryController@getDocumentSecretary')->name('secretary-view-document');


    /* ---------------------- thilan -------------------------*/       
    Route::post('/society-data-submit', 'Society\SocietyController@saveSocietyData')->name('society-data-submit');
    Route::post('/society-profile-load', 'Society\SocietyController@loadRegisteredSocietyData')->name('society-profile-load'); 
    Route::post('/society-pay', 'Society\SocietyController@societyPay')->name('society-pay');      
    
});
