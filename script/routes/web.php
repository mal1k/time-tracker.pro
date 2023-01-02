<?php

use App\Mail\AssignNewProjectMail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Route;

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


Auth::routes(['verify' => false]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');
Route::resource('blog', 'BlogController');
Route::resource('about', 'AboutController');
Route::resource('analytic', 'AnalyticController');
Route::resource('feature', 'FeatureController');
Route::get('pricing', 'HomeController@pricing');
Route::get('page/{slug}', 'HomeController@page');

Route::get('lang/set','LanguageController@set')->name('lang.set');

Route::get('plan/{planid}', 'HomeController@check')->name('plan.check');

Route::get('invite/register', 'User\LoginController@registerFromInvite')->name('invite.register');
Route::get('user/register', 'User\LoginController@register_form')->name('user.register');

Route::post('/user/plan/register', 'User\LoginController@plan_register')->name('user.plan.register');

Route::post('invite/register/', 'User\LoginController@register')->name('invite.register.store');

//To check if storage limit exceeded and update screenshot status
Route::get('/storage/check','User\ProjectsController@storageCheck')->name('project.storage');

Route::post('/subscribe', 'HomeController@subscribe')->name('newsletter');

Route::get('cron/alert-user/after/order/expired', 'Admin\OrderController@alertUserAfterExpiredOrder')->name('alert.after.order.expired');
Route::get('cron/alert-user/before/order/expired', 'Admin\OrderController@alertUserBeforeExpiredOrder')->name('alert.before.order.expired');

Route::get('/admin/login', 'Admin\LoginController@login')->middleware('guest')->name('admin.login');

Route::get('/admin/logout', 'Auth\LoginController@logout', 'logout')->middleware('auth')->name('admin.logout');
Route::get('/logout', 'User\LoginController@logout', 'logout')->middleware('auth')->name('logout');

Route::get('contact','ContactController@index')->name('contact.index');
Route::post('sendmail','ContactController@send')->name('contact.send');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin','middleware' => ['auth','admin']], function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('get_monthly_order/{month}', 'DashboardController@getMonthlyOrders');
    Route::get('earning_performance/{period}', 'DashboardController@earningPerformance');
    Route::get('get_static_data', 'DashboardController@getStaticData');
    //Support Route
    Route::resource('plan', 'PlanController');

    //Support Route
    Route::resource('support', 'SupportController');
    Route::post('supportInfo', 'SupportController@getSupportData')->name('support.info');
    Route::post('supportstatus', 'SupportController@supportStatus')->name('support.status');

    // Pages Route
    Route::resource('page', 'PageController');

    //Blog Route
    Route::resource('blog', 'BlogController');

    //Coupon Route
    Route::resource('coupon', 'CouponController');

    //Coupon Route
    Route::get('order/search', 'OrderController@search')->name('order.search');
    Route::get('order/search/{string}', 'OrderController@search')->name('order.filter');
    Route::get('order-active/{id}', 'OrderController@active')->name('order.active');
    Route::get('order-deactive/{id}', 'OrderController@deactive')->name('order.deactive');
    Route::post('order-status', 'OrderController@status')->name('order.status');
    Route::resource('order', 'OrderController');

    //Transaction Route
    Route::resource('transaction', 'TransactionController');

    //Transaction Route
    Route::resource('project', 'ProjectController');

    //Transaction Route
    Route::resource('client', 'ClientController');

    //Transaction Route
    Route::resource('report', 'ReportController');
    Route::post('report/data', 'ReportController@reportData')->name('report.data');

    //PaymentGateway Route
    Route::resource('paymentgateway', 'PaymentGatewayController');

    //Support Route
    Route::resource('support', 'SupportController');

    //Option route
    Route::get('option/edit/{key}', 'OptionController@edit')->name('option.edit');
    Route::post('option/update/{key}', 'OptionController@update')->name('option.update');

    //User Route
    Route::resource('user', 'UserController');
    Route::post('users/status', 'UserController@status')->name('users.status');
    Route::get('user/login/{id}', 'UserController@login')->name('user.login');
    Route::post('user/mail/{id}', 'UserController@sendmail')->name('user.mail');
    Route::post('user/details/', 'UserController@details')->name('user.details');
    Route::get('user/plan/{id}', 'UserController@planEdit')->name('user.editplan');
    Route::post('user/plan/update/{id}', 'UserController@planUpdate')->name('user.editplan.update');

    Route::get('user/invoice/{id}', 'UserController@invoice')->name('user.invoice');

    // Roles Route
	Route::resource('role', 'RoleController');
    Route::post('roles/destroy', 'RoleController@destroy')->name('roles.destroy');

    // Menu Route
	Route::resource('menu', 'MenuController');
    Route::post('/menus/destroy', 'MenuController@destroy')->name('menus.destroy');
    Route::post('menues/node', 'MenuController@MenuNodeStore')->name('menus.MenuNodeStore');

    //Review and rating controller
    Route::resource('review', 'ReviewController');

    Route::get('setting/env', 'EnvController@index');
    Route::post('setting_env', 'EnvController@store')->name('env.store');

    Route::resource('setting/seo', 'SeoController');

    Route::resource('feature', 'FeatureController');

    Route::resource('about', 'AboutSectionController');

    Route::resource('header', 'HeaderController');

    Route::resource('analytic', 'AnalyticSectionController');

    Route::get('theme/settings', 'OptionController@settingsEdit')->name('theme.settings');
    Route::put('theme/settings-update/{id}', 'OptionController@settingsUpdate')->name('theme.settings.update');

    // Admin Route
	Route::resource('admin', 'AdminController');
    Route::post('/admins/destroy', 'AdminController@destroy')->name('admins.destroy');

    // Language Route
    Route::resource('language', 'LanguageController');
    Route::post('language/set','LanguageController@lang_set')->name('language.set');
    Route::post('language/key_store','LanguageController@key_store')->name('key_store');
});


Route::group(['prefix' => 'user', 'as' => 'user.', 'namespace' => 'User', 'middleware' => ['auth','user']], function () {

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('recent/stats/', 'DashboardController@recentChartStats')->name('recent.stats');
	Route::get('/client/index', 'ClientsController@index')->name('client.index');
	Route::get('/tag/index', 'TagsController@index')->name('tag.index');
    Route::post('user/details', 'DashboardController@userDetails')->name('details');
    Route::post('user/getNotification', 'DashboardController@getNotification')->name('notification');

    //team routes
	Route::get('/team/index', 'TeamController@index')->name('team.index');
	Route::get('/team/member', 'TeamController@member')->name('team.member');
	Route::get('/team/reminder', 'TeamController@reminder')->name('team.reminder');
	Route::get('/team/collaboration', 'TeamController@collaboration')->name('team.collaboration');
    Route::get('/team/collaboration/activate', 'TeamController@collaboration_activate')->name('team.collab.activate');
    Route::get('/team/collaboration/search', 'TeamController@collaboration_search')->name('team.collab.search');
    Route::post('/team/collaboration_delete/{id}', 'TeamController@collaboration_delete')->name('team.collaboration.delete');


	Route::post('/team/store', 'TeamController@store')->name('team.store');
	Route::get('/team/edit/{member}', 'TeamController@edit')->name('team.edit');
	Route::post('/team/edit/{member}', 'TeamController@update')->name('team.update');
	Route::post('/team/delete/{member}', 'TeamController@delete')->name('team.delete');
	Route::get('/team/members/', 'TeamController@search')->name('team.member.search');


    //Group Routes
    Route::get('/team/group', 'TeamController@group')->name('team.group');
    Route::post('/team/group/store', 'TeamController@groupStore')->name('group.store');
    Route::post('/team/group/delete/{id}', 'TeamController@groupDelete')->name('group.delete');
    Route::get('/team/group/edit/{id}', 'TeamController@groupEdit')->name('group.edit');
    Route::put('/team/group/update/{id}', 'TeamController@groupUpdate')->name('group.update');
    Route::get('/team/groups/', 'TeamController@groupsearch')->name('team.group.search');
	Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/projects', 'ProjectsController@search')->name('project.search');
    Route::resource('project', 'ProjectsController');

    //Support Route
    Route::resource('support', 'SupportController');
    Route::post('supportInfo', 'SupportController@getSupportData')->name('support.info');
    Route::post('supportstatus', 'SupportController@supportStatus')->name('support.status');


    Route::get('project/show-user/{id}/{task_id}/{seen}', 'ProjectsController@showUserFromMail')->name('project.show.usermail');
    Route::get('project/show-user/{id}/{seen}', 'ProjectsController@projectSeenView')->name('project.show.userinvite');

    Route::post('project/addColumn/', 'ProjectsController@addColumn')->name('project.addColumn');
    Route::post('project/sortColumn/', 'ProjectsController@sortColumn')->name('project.sortColumn');
    Route::put('project/updateColumn/{id}', 'ProjectsController@updateColumn')->name('project.updateColumn');
    Route::post('project/deleteColumn/{id}', 'ProjectsController@deleteColumn')->name('project.deleteColumn');
    Route::post('project/addTask/', 'ProjectsController@addTask')->name('project.addTask');
    Route::post('project/sortTask/', 'ProjectsController@sortTask')->name('project.sortTask');
    Route::post('project/updateTask/', 'ProjectsController@updateTask')->name('project.update_task');
    Route::post('project/updateTaskstatus/', 'ProjectsController@updateTaskStatus')->name('project.updateTaskStatus');
    Route::post('project/updateTaskSt/', 'ProjectsController@updateTaskSt')->name('project.update.task.status');

    Route::post('project/assignUserTask/', 'ProjectsController@assignUserTask')->name('project.assignUserTask');
    Route::post('project/updateTaskDueDate/', 'ProjectsController@updateTaskDueDate')->name('project.updateTaskDueDate');
    Route::post('project/updateTaskPriority/', 'ProjectsController@updateTaskPriority')->name('project.updateTaskPriority');
    Route::post('project/allTodos/', 'ProjectsController@allTodos')->name('project.allTodos');
    Route::post('project/updateTodoTask/', 'ProjectsController@updateTodoTask')->name('project.updateTodoTask');
    Route::post('project/deleteTask/{id}', 'ProjectsController@deleteTask')->name('project.deleteTask');
    Route::post('project/addCommentOnTask/', 'ProjectsController@addCommentOnTask')->name('project.addCommentOnTask');
    Route::post('project/assignAdmin/', 'ProjectsController@assignAdmin')->name('project.assignAdmin');

    Route::post('project/updateColumnStatus/', 'ProjectsController@updateColumnStatus')->name('project.updateColumnStatus');

    Route::post('project/update-status', 'ProjectsController@projectStatus')->name('project.status');

    Route::resource('task', 'TaskController');
    Route::post('task/loadtasks/', 'TaskController@loadTasks')->name('task.loadTasks');
    // Route::get('task/loadtasks/', 'TaskController@loadTasks')->name('task.loadTasks');
    Route::post('task/loadTasksProjectWise/', 'TaskController@loadTasksProjectWise')->name('task.loadTasksProjectWise');
    Route::post('task/taskModalData/', 'TaskController@taskModalData')->name('task.modal.data');
    Route::post('task/addCommentOnTask/', 'TaskController@addCommentOnTask')->name('task.addCommentOnTask');

    //calender routes
    Route::resource('calender', 'CalenderController');
    Route::post('calender/tasks/', 'CalenderController@renderTask')->name('calender.renderTask');

    //time tracker routes
    Route::get('time-tracker', 'TimeTrackerController@index')->name('time.tracker');
    Route::post('upload/screenshot', 'TimeTrackerController@uploadScreenshot')->name('upload.screenshot');
    Route::post('trackuser', 'TimeTrackerController@trackuser')->name('time.trackuser');
    Route::get('/task/start/{project_id}/{task_id}', 'TimeTrackerController@taskTracker')->name('task.tracker');
    Route::post('gps/store/', 'TimeTrackerController@gpsStore')->name('gps.store');
    Route::post('time/start/', 'TimeTrackerController@timestart')->name('time.start');
    Route::post('time/stop/', 'TimeTrackerController@timestop')->name('time.stop');
    Route::post('get/tasks/', 'TimeTrackerController@getTasks')->name('get.tasks');

    //report routes
    Route::post('report/stats', 'ReportController@stats')->name('report.stats');
    Route::post('report/render-task', 'ReportController@rendertask')->name('report.rendertask');
    Route::get('report/column/{id}', 'ReportController@columnReport')->name('report.column');
    Route::get('report/user/{id}/{project_id}', 'ReportController@userReport')->name('report.user');
    Route::get('report/user-stats', 'ReportController@userStats')->name('report.user.stats');
    Route::post('report/fetch-stats', 'ReportController@getStats')->name('report.fetch.stats');
    Route::post('report/fetch-gps', 'ReportController@getGps')->name('report.fetch.gps');
    Route::post('report/attachments', 'ReportController@attachments')->name('report.attachments');
    Route::get('report/attachment-list/{id}', 'ReportController@attachmentList')->name('report.attachment.list');
    Route::post('report/delete/screenshot/', 'ReportController@deleteScreenshot')->name('report.delete.screenshot');
    Route::get('report/search/', 'ReportController@search')->name('report.search');
    Route::resource('report', 'ReportController');

    //Settings routes
    Route::get('settings/index', 'SettingController@index')->name('settings.index');
    Route::get('settings/alerts', 'SettingController@alerts')->name('settings.alerts');
    Route::get('settings/accounts', 'SettingController@accounts')->name('settings.accounts');
    Route::get('settings/authentication', 'SettingController@authentication')->name('settings.authentication');
    Route::get('settings/customfields', 'SettingController@customfields')->name('settings.customfields');
    Route::get('settings/import', 'SettingController@import')->name('settings.import');

    //Plan Routes
    Route::get('plan/subscription/', 'PlanController@subscription')->name('plan.subscribe');
    Route::get('plan/gateways/{planid}', 'PlanController@gateways')->name('plan.gateways');
    Route::get('plan/subscribe/', 'PlanController@subscription')->name('gaplan.sub');
    Route::post('plan/deposit/', 'PlanController@deposit')->name('plan.deposit');
    Route::get('plan/payment/history', 'PlanController@history')->name('plan.history');
    Route::resource('plan','PlanController');
    
    Route::get('payment/success', 'PlanController@success')->name('payment.success');
    Route::get('payment/fail', 'PlanController@fail')->name('payment.fail');

});








//**==================================== Payment Gateway Route Group =============================================**//

Route::group(['middleware' => ['auth']], function () {
    Route::post('/user/stripe/payment', '\App\Lib\Stripe@status')->name('stripe.payment');
    Route::get('/user/stripe/view', '\App\Lib\Stripe@view')->name('stripe.view');
    Route::get('/payment/paypal','\App\Lib\Paypal@status');
    Route::get('/user/razorpay/payment','\App\Lib\Razorpay@view');
    Route::post('/user/razorpay/status','\App\Lib\Razorpay@status');
    Route::get('/payment/mollie','\App\Lib\Mollie@status');
    Route::get('/payment/instamojo','\App\Lib\Instamojo@status');
    Route::get('/payment/toyyibpay','\App\Lib\Toyyibpay@status');
    Route::get('/user/paystack/payment','\App\Lib\Paystack@view');
    Route::post('/payment/paystack','\App\Lib\Paystack@status');
    Route::get('/payment/flutterwave','\App\Lib\Flutterwave@status');
    Route::get('user/manual/payment', '\App\Lib\CustomGetway@status');
    Route::get('/profile','ProfileController@profile');
    Route::post('update-profile','ProfileController@update')->name('user.profile.update');

});
