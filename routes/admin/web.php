<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\LogicController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ManageUsersController;
use App\Http\Controllers\Admin\ManageDepositController;
use App\Http\Controllers\Admin\ManageWithdrawalController;
use App\Http\Controllers\Admin\InvPlanController;
use App\Http\Controllers\Admin\ManageAdminController;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\Settings\AppSettingsController;
use App\Http\Controllers\Admin\Settings\ReferralSettings;
use App\Http\Controllers\Admin\Settings\PaymentController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\Settings\SubscriptionSettings;
use App\Http\Controllers\Admin\IpaddressController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\ClearCacheController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\ManageAssetController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\TopupController;
use Illuminate\Support\Facades\Route;

// Include admin plan routes
require __DIR__ . '/plan-routes.php';

// Admin Login Routes validate_admin

Route::prefix('adminlogin')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('remedy', 'showLoginForm')->name('adminloginform')->middleware('adminguest');
        Route::post('login', 'adminlogin')->name('adminlogin');
        Route::post('logout', 'adminlogout')->name('adminlogout');
        Route::get('dashboard', 'validate_admin')->name('validate_admin');
         Route::get('validate_admin', 'showLoginForm')->name('adminloginform')->middleware('adminguest');
    });
});

Route::controller(TwoFactorController::class)->group(function () {
    // Two Factor controller for Admin.
    Route::get('admin/2fa', 'showTwoFactorForm')->name('2fa');
    Route::post('admin/twofa', 'verifyTwoFactor')->name('twofalogin');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('admin/forgot-password', 'forgotPassword')->name('admin.forgetpassword');
    Route::post('admin/send-request', 'sendPasswordRequest')->name('sendpasswordrequest');
    Route::get('/admin/reset-password/{email}', 'resetPassword')->name('resetview');
    Route::post('/reset-password-admin', 'validateResetPasswordToken')->name('restpass');
});

Route::middleware(['isadmin', '2fa'])->prefix('admin')->group(function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('dashboard', 'index')->name('admin.dashboard');
        Route::get('dashboard/plans', 'plans')->name('plans');
        Route::get('dashboard/new-plan', 'newplan')->name('newplan');
        Route::get('dashboard/edit-plan/{id}', 'editplan')->name('editplan');
        Route::get('dashboard/manageusers', 'manageusers')->name('manageusers');
        Route::get('/dashboard/active-investments', 'activeInvestments')->name('activeinvestments');
        Route::get('/dashboard/investments', 'Investments')->name('investments');

        // CRM ROUTES
        Route::get('dashboard/calendar', 'calendar')->name('calendar');
        Route::get('dashboard/task', 'showtaskpage')->name('task');
        Route::get('dashboard/mtask', 'mtask')->name('mtask');
        Route::get('dashboard/viewtask', 'viewtask')->name('viewtask');
        Route::get('dashboard/customer', 'customer')->name('customer');
        Route::get('dashboard/leads', 'customer')->name('leads');
        Route::get('dashboard/leadsassign', 'customer')->name('leadsassign');
        Route::get('dashboard/user-plans/{id}',  'userplans')->name('user.plans');
        Route::get('dashboard/investments/{id}',  'investmentplans')->name('user.investments');
        Route::get('dashboard/email-services',  'emailServices')->name('emailservices');
        Route::get('dashboard/about',  'aboutonlinetrade')->name('aboutonlinetrade');
        Route::get('dashboard/mwithdrawals', 'mwithdrawals')->name('mwithdrawals');
        Route::get('dashboard/mdeposits', 'mdeposits')->name('mdeposits');
        Route::get('dashboard/agents',  'agents')->name('agents');
        Route::get('dashboard/addmanager', 'addmanager')->name('addmanager');
        Route::get('dashboard/madmin', 'madmin')->name('madmin');
        Route::get('dashboard/settings', 'settings')->name('settings');
        Route::get('dashboard/frontpage', 'frontpage')->name('frontpage');
        Route::get('dashboard/adduser', 'adduser')->name('adduser');
        // KYC Routes
        Route::get('dashboard/kyc-applications', 'kyc')->name('kyc');
        Route::get('dashboard/kyc-application/{id}', 'viewKycApplication')->name('viewkyc');
        Route::get('dashboard/adminprofile', 'adminprofile')->name('adminprofile');
    });

    Route::controller(KycController::class)->group(function () {
        Route::post('dashboard/processkyc', 'processKyc')->name('processkyc');
    });

    Route::controller(CrmController::class)->group(function () {
        Route::post('dashboard/addtask', 'addtask')->name('addtask');
        Route::post('dashboard/updatetask', 'updatetask')->name('updatetask');
        Route::get('dashboard/deltask/{id}', 'deltask')->name('deltask');
        Route::get('dashboard/markdone/{id}', 'markdone')->name('markdone');
        Route::post('dashboard/updateuser', 'updateuser')->name('updateuser');
        Route::get('dashboard/convert/{id}', 'convert')->name('convert');
        Route::post('dashboard/assign', 'assign')->name('assignuser');
    });

    Route::controller(ManageUsersController::class)->group(function () {
        Route::get('dashboard/user-wallet/{id}', 'userwallet')->name('user.wallet');
        Route::get('dashboard/fetchusers', 'fetchUsers')->name('fetchusers');
        Route::post('dashboard/sendmailsingle', 'sendmailtooneuser')->name('sendmailtooneuser');
        Route::post('dashboard/notifyuser', 'notifyuser')->name('notifyuser');
        Route::post('dashboard/upgradesignalstatus', 'upgradesignalstatus')->name('upgradesignalstatus');
        Route::post('dashboard/upgradeplanstatus', 'upgradeplanstatus')->name('upgradeplanstatus');
        Route::post('dashboard/AddHistory', 'addHistory')->name('addhistory');
        Route::post('dashboard/AddPlanHistory', 'addplanhistory')->name('addplanhistory');
         Route::post('dashboard/withdrawalcode', 'withdrawalcode')->name('withdrawalcode');
        Route::post('dashboard/edituser', 'edituser')->name('edituser');
        Route::post('dashboard/usertax', 'usertax')->name('usertax');
        Route::post('dashboard/numberoftrades', 'numberoftrades')->name('numberoftrades');
         Route::get('dashboard/getusers/{num}/{item}/{order}', 'getusers')->name('getusers');
        Route::get('dashboard/resetpswd/{id}', 'resetpswd')->name('resetpswd');
        Route::get('dashboard/login-activity/{id}', 'loginactivity')->name('loginactivity');
        Route::get('dashboard/clear-activity/{id}', 'clearactivity')->name('clearactivity');
        Route::get('dashboard/add-referral/{id}', 'showUsers')->name('showusers');
        Route::post('dashboard/add-referral', 'addReferral')->name('addref');
        Route::get('dashboard/switchuser/{id}', 'switchuser');
        Route::get('dashboard/clearacct/{id}', 'clearacct')->name('clearacct');
        Route::post('dashboard/saveuser', 'saveuser')->name('createuser');
        Route::get('dashboard/user-details/{id}', 'viewuser')->name('viewuser');
        Route::get('dashboard/email-verify/{id}', 'emailverify')->name('emailverify');
        Route::get('dashboard/uublock/{id}', 'ublock');
        Route::get('dashboard/uunblock/{id}', 'unblock');

        Route::get('dashboard/delsystemuser/{id}', 'delsystemuser');
        Route::get('dashboard/usertrademode/{id}/{action}', 'usertrademode');
        Route::post('dashboard/sendmailtoall', 'sendmailtoall')->name('sendmailtoall');
        Route::get('dashboard/deleteplan/{id}', 'deleteplan')->name('deleteplan');
        Route::get('dashboard/markprofit/{id}', 'markprofit')->name('markprofit');
        Route::get('dashboard/markloss/{id}', 'markloss')->name('markloss');
        Route::get('dashboard/approveplan/{id}', 'approvePlan')->name('approveplan');
        Route::get('dashboard/markas/{status}/{id}', 'markplanas')->name('markas');
    });


    Route::controller(ManageDepositController::class)->group(function () {
        Route::get('dashboard/deldeposit/{id}', 'deldeposit')->name('deldeposit');
        Route::get('dashboard/pdeposit/{id}', 'pdeposit')->name('pdeposit');
        Route::get('dashboard/viewimage/{id}', 'viewdepositimage')->name('viewdepositimage');
        Route::post('dashboard/editamount', 'editamount')->name('editamount');
    });

    Route::controller(ManageWithdrawalController::class)->group(function () {
        Route::post('dashboard/pwithdrawal', 'pwithdrawal')->name('pwithdrawal');
        Route::get('dashboard/process-withdrawal-request/{id}', 'processwithdraw')->name('processwithdraw');
    });

    Route::controller(PaymentController::class)->group(function () {
        // Payment settings
        Route::post('dashboard/addwdmethod', 'addpaymethod')->name('addpaymethod');
        Route::put('dashboard/updatewdmethod', 'updatewdmethod');
        Route::get('dashboard/edit-method/{id}', 'editmethod')->name('editpaymethod');
        Route::get('dashboard/delete-method/{id}', 'deletepaymethod')->name('deletepaymethod');
        //enable and disbale payment method routes
        Route::get('dashboard/toggle-method-status/{id}', 'togglePaymentMethodStatus')->name('togglestatus');
        Route::put('dashboard/update-method', 'updatemethod')->name('updatemethod');
        Route::put('dashboard/paypreference', 'paypreference')->name('paypreference');
        Route::put('dashboard/updatecpd', 'updatecpd')->name('updatecpd');
        Route::put('dashboard/updategateway', 'updategateway')->name('updategateway');
        Route::put('dashboard/update-transfer-settings', 'updateTransfer')->name('updatetransfer');
        Route::get('dashboard/settings/payment-settings', 'paymentview')->name('paymentview');
    });

    Route::controller(TopupController::class)->group(function () {
        Route::post('dashboard/topup', 'topup')->name('topup');
    });



	///wallet-connect



    Route::controller(IpaddressController::class)->group(function () {
        Route::get('dashboard/ipaddress', 'index')->name('ipaddress');
        Route::get('dashboard/allipaddress', 'getaddress')->name('allipaddress');
        Route::get('dashboard/delete-ip/{id}', 'deleteip')->name('deleteip');
        Route::post('dashboard/add-ip', 'addipaddress')->name('addipaddress');
    });

    // Route::controller(SettingsController::class)->group(function () {
    //     Route::post('dashboard/updatesettings', 'updatesettings');
    //     Route::post('dashboard/updateasset', 'updateasset');
    //     Route::post('dashboard/updatemarket', 'updatemarket');
    //     Route::post('dashboard/updatefee', 'updatefee');
    //     Route::get('dashboard/deletewdmethod/{id}', 'deletewdmethod');
    // });

    Route::controller(ManageAdminController::class)->group(function () {
        Route::get('dashboard/unblock/{id}', 'unblockadmin');
        Route::get('dashboard/ublock/{id}', 'blockadmin');
        Route::get('dashboard/deleletadmin/{id}', 'deleteadminacnt')->name('deleteadminacnt');
        Route::post('dashboard/editadmin', 'editadmin')->name('editadmin');
        Route::get('dashboard/adminchangepassword', 'adminchangepassword');
        Route::post('dashboard/adminupdatepass', 'adminupdatepass')->name('adminupdatepass');
        Route::get('dashboard/resetadpwd/{id}', 'resetadpwd')->name('resetadpwd');
        Route::post('dashboard/sendmail', 'sendmail')->name('sendmailtoadmin');
        Route::post('dashboard/changestyle', 'changestyle')->name('changestyle');
        Route::post('dashboard/saveadmin', 'saveadmin');
        Route::post('dashboard/update-profile', 'updateadminprofile')->name('upadprofile');
    });

    Route::controller(FrontendController::class)->group(function () {
        // This Route is for frontpage editing
        Route::post('dashboard/savefaq', 'savefaq')->name('savefaq');
        Route::post('dashboard/savetestimony', 'savetestimony')->name('savetestimony');
        Route::post('dashboard/saveimg', 'saveimg')->name('saveimg');
        Route::post('dashboard/savecontents', 'savecontents')->name('savecontents');
        //Update Frontend Pages
        Route::post('dashboard/updatefaq', 'updatefaq')->name('updatefaq');
        Route::post('dashboard/updatetestimony', 'updatetestimony')->name('updatetestimony');
        Route::post('dashboard/updatecontents', 'updatecontents')->name('updatecontents');
        Route::post('dashboard/updateimg', 'updateimg')->name('updateimg');
        // Delete fa and tes routes
        Route::get('dashboard/delfaq/{id}', 'delfaq');
        Route::get('dashboard/deltestimony/{id}', 'deltest');
        // privacy policy
        Route::get('dashboard/privacy-policy', 'termspolicy')->name('termspolicy');
        Route::post('dashboard/privacy-policy', 'savetermspolicy')->name('savetermspolicy');
    });

    Route::controller(InvPlanController::class)->group(function () {
        Route::post('dashboard/addplan', 'addplan')->name('addplan');
        Route::post('dashboard/updateplan', 'updateplan')->name('updateplan');
        Route::get('dashboard/trashplan/{id}', 'trashplan')->name('trashplan');
    });

    // Route::controller(LogicController::class)->group(function () {
    //     Route::post('dashboard/addagent', 'addagent');
    //     Route::get('dashboard/viewagent/{agent}', 'viewagent')->name('viewagent');
    //     Route::get('dashboard/delagent/{id}', 'delagent')->name('delagent');
    // });

    Route::controller(AppSettingsController::class)->group(function () {
        // Update App Information
        Route::put('dashboard/updatewebinfo', 'updatewebinfo')->name('updatewebinfo');
        Route::put('dashboard/updatepreference', 'updatepreference')->name('updatepreference');
        Route::put('dashboard/updateemail', 'updateemail')->name('updateemailpreference');
        // Settings Routes
        Route::get('dashboard/settings/app-settings', 'appsettingshow')->name('appsettingshow');
        Route::post('update-theme', 'updateTheme')->name('theme.update');
    });

    Route::controller(ReferralSettings::class)->group(function () {
        // Update referral settings info
        Route::put('dashboard/update-bonus', 'updaterefbonus')->name('updaterefbonus');
        Route::get('dashboard/settings/referral-settings', 'referralview')->name('refsetshow');
        // Update other bonus settings info
        Route::put('dashboard/other-bonus', 'otherBonus')->name('otherbonus');
    });

    Route::controller(ImportController::class)->group(function () {
        Route::get('download-doc', 'downloadDoc')->name('downlddoc');
        // This route is to import data from excel
        Route::post('dashboard/fileImport', 'fileImport')->name('fileImport');
    });

    Route::controller(SubscriptionSettings::class)->group(function () {
        Route::put('dashboard/updatesubfee', 'updatesubfee')->name('updatesubfee');
        Route::get('dashboard/settings/subscription-settings', 'index')->name('subview');
    });

    Route::controller(ManageAssetController::class)->group(function () {
        Route::get('dashboard/setcryptostatus/{asset}/{status}', 'setassetstatus')->name('setassetstatus');
    });


    Route::controller(MembershipController::class)->group(function () {
        //memebership module
        Route::get('/courses', 'showCourses')->name('courses');
        Route::post('/add-course', 'addCourse')->name('addcourse');
        Route::patch('/update-course', 'updateCourse')->name('updatecourse');
        Route::get('/delete-course/{id}', 'deleteCourse')->name('deletecourse');

        Route::get('/courses-lessons/{id}', 'showLessons')->name('lessons');
        Route::post('/add-lesson', 'addLesson')->name('addlesson');
        Route::patch('/update-lesson', 'updateLesson')->name('updatedlesson');
        Route::get('/delete-lesson/{id}', 'deleteLesson')->name('deletelesson');

        Route::get('/categories', 'category')->name('categories');
        Route::post('/add-category', 'addCategory')->name('addcategory');
        Route::get('/delete-cat/{id}', 'deleteCategory')->name('deletecategory');
        Route::get('lessons-without-course', 'lessonWithoutCourse')->name('less.nocourse');
    });


    // clear cache
    Route::get('dashboard/clearcache', [ClearCacheController::class, 'clearcache'])->name('clearcache');

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('admin.notifications');
        Route::get('/send-message', [AdminNotificationController::class, 'showSendMessageForm'])->name('admin.send.message.form');
        Route::post('/send-message', [AdminNotificationController::class, 'sendMessage'])->name('admin.send.message');
        Route::get('/mark-read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('admin.markasread');
        Route::get('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.markallasread');
        Route::get('/delete/{id}', [AdminNotificationController::class, 'delete'])->name('admin.deletenotification');
        Route::get('/count', [AdminNotificationController::class, 'getUnreadCount'])->name('admin.notifications.count');
    });
});

// (Investment Plans module retired — citizens invest directly in assets.)

// Asset Management (Republic of Botswana investment platform — Phase 2)
Route::middleware(['isadmin', '2fa'])->prefix('admin')->group(function () {
    Route::controller(\App\Http\Controllers\Admin\AssetController::class)
        ->prefix('assets')->name('admin.assets.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{asset}/edit', 'edit')->name('edit');
            Route::put('/{asset}', 'update')->name('update');
            Route::delete('/{asset}', 'destroy')->name('destroy');
            Route::post('/{asset}/toggle', 'toggleStatus')->name('toggle');
            Route::delete('/document/{document}', 'destroyDocument')->name('document.destroy');
            Route::get('/document/{document}/download', 'downloadDocument')->name('document.download');
        });
});

// Payout Management (Republic of Botswana investment platform — Phase 6)
Route::middleware(['isadmin', '2fa'])->prefix('admin')->group(function () {
    Route::controller(\App\Http\Controllers\Admin\PayoutController::class)
        ->prefix('payouts')->name('admin.payouts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/process-due', 'processDue')->name('process-due');
            Route::post('/{investment}/mature', 'markMatured')->name('mature');
        });
});

// Investment back-office: dashboard + reports (Republic of Botswana — Phase 7)
Route::middleware(['isadmin', '2fa'])->prefix('admin')->group(function () {
    Route::get('investment-dashboard', [\App\Http\Controllers\Admin\InvestmentDashboardController::class, 'index'])
        ->name('admin.investment-dashboard');

    Route::controller(\App\Http\Controllers\Admin\ReportController::class)
        ->prefix('reports')->name('admin.reports.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/investments', 'investments')->name('investments');
            Route::get('/payouts', 'payouts')->name('payouts');
            Route::get('/investors', 'investors')->name('investors');
        });
});
