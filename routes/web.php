<?php

use App\Models\Language;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Installer routes
Route::get('/install/{step?}', [App\Http\Controllers\InstallerController::class, 'index'])->name('install');
Route::post('/install/configure-database', [App\Http\Controllers\InstallerController::class, 'configureDatabase']);
Route::post('/install/configure-company', [App\Http\Controllers\InstallerController::class, 'configureCompany']);
Route::post('/install/migrate', [App\Http\Controllers\InstallerController::class, 'runMigrations']);
Route::get('/update', [App\Http\Controllers\InstallerController::class, 'update'])->name('install.update');
Route::post('/update', [App\Http\Controllers\InstallerController::class, 'runUpdate']);

Route::get('/current-locale', function () {
    return response()->json(['locale' => app()->getLocale()]);
});

Route::get('/locales', function () {
    $locales = Language::all()->pluck('code');
    return response()->json($locales);
});

Route::get('/translations/{locale}', function ($locale) {
    $path = base_path("lang/{$locale}.json");
    if (File::exists($path)) {
        $translations = File::get($path);
        return response()->json(json_decode($translations, true));
    } else {
        return response()->json(['error' => 'Locale not found'], 404);
    }
});

//Frontend Routes
Route::get('/', function () {
    return redirect('/login');
});
Route::match(['get', 'post'], '/', [App\Http\Controllers\FrontendController::class, 'index']);
Route::match(['get', 'post'], '/pages/{slug}', [App\Http\Controllers\FrontendController::class, 'pages']);
Route::match(['get', 'post'], '/privacy', [App\Http\Controllers\FrontendController::class, 'privacy']);
Route::match(['get', 'post'], '/terms-of-service', [App\Http\Controllers\FrontendController::class, 'termsOfService']);
Route::match(['get', 'post'], '/process-campaign', [App\Http\Controllers\FrontendController::class, 'buildTemplateChatMessage']);
Route::get('/language/{locale}', [App\Http\Controllers\FrontendController::class, 'changeLanguage']);

//File Route
Route::get('media/{filename}', [App\Http\Controllers\FileController::class, 'show'])->where('filename', '.*');

//Invite Routes
Route::get('/invite/{identifier}', [App\Http\Controllers\AuthController::class, 'viewInvite']);
Route::post('/invite/{identifier}', [App\Http\Controllers\AuthController::class, 'invite']);

Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

//Webhook
Route::match(['get', 'post'], '/webhook/whatsapp/{identifier?}', [App\Http\Controllers\WebhookController::class, 'handle']);
Route::match(['get', 'post'], '/webhook/waba', [App\Http\Controllers\WebhookController::class, 'whatsappWebhook']);
Route::match(['get', 'post'], '/webhook/{processor}', [App\Http\Controllers\WebhookController::class, 'processWebhook']);
Route::match(['get', 'post'], '/payment/{processor}', [App\Http\Controllers\PaymentController::class, 'processPayment']);

Route::get('/campaign-send', [App\Http\Controllers\FrontendController::class, 'sendCampaign']);
Route::get('/migrate-upgrade', [App\Http\Controllers\FrontendController::class, 'migrate']);

Route::middleware(['guest', 'redirectIfAuthenticated:user,admin'])->group(function () {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::get('/tfa', [App\Http\Controllers\AuthController::class, 'showTfaForm']);
    Route::post('/tfa', [App\Http\Controllers\AuthController::class, 'tfaVerify']);
    Route::get('/social-login/{type?}', [App\Http\Controllers\AuthController::class, 'socialLogin']);
    Route::get('/google/callback', [App\Http\Controllers\AuthController::class, 'googleCallback'])->name('google.callback');
    Route::get('/facebook/callback', [App\Http\Controllers\AuthController::class, 'handleFacebookCallback']);
    Route::get('/signup', [App\Http\Controllers\AuthController::class, 'showRegistrationForm']);
    Route::post('/signup', [App\Http\Controllers\AuthController::class, 'handleRegistration']);
    Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotForm']);
    Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'createPasswordResetToken']);
    Route::get('/reset-password', [App\Http\Controllers\AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:user,admin'])->group(function () {
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update']);
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword']);
    Route::put('/profile/tfa', [App\Http\Controllers\ProfileController::class, 'updateTfa']);
    Route::put('/profile/organization', [App\Http\Controllers\ProfileController::class, 'updateOrganization']);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('/email/verify', [App\Http\Controllers\AuthController::class, 'verifyEmail'])->middleware('auth')->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', [App\Http\Controllers\AuthController::class, 'sendEmailVerification'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::group(['middleware' => ['check.email.verification']], function () {
        Route::get('/select-organization', [App\Http\Controllers\User\OrganizationController::class, 'index'])->name('user.organization.index');
        Route::post('/organization', [App\Http\Controllers\User\OrganizationController::class, 'store'])->name('user.organization.store');

        Route::group(['middleware' => ['check.organization']], function () {
            //User Panel Routes
            Route::match(['get', 'post'], '/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

            Route::group(['middleware' => 'check.client.role'], function () {
                Route::delete('dismiss-notification/{type}', [App\Http\Controllers\User\DashboardController::class, 'dismissNotification'])->name('dashboard.team.notification.dismiss');
                Route::match(['get', 'post'], '/billing', [App\Http\Controllers\User\BillingController::class, 'index'])->name('user.billing.index');
                Route::post('/pay', [App\Http\Controllers\User\BillingController::class, 'pay'])->name('user.billing.pay');
                Route::resource('subscription', App\Http\Controllers\User\SubscriptionController::class);
                Route::post('/subscription/coupon/apply/{id}', [App\Http\Controllers\User\SubscriptionController::class, 'applyCoupon']);
                Route::get('/subscription/coupon/remove/{id}', [App\Http\Controllers\User\SubscriptionController::class, 'removeCoupon']);
            });

            Route::group(['middleware' => 'check.subscription'], function () {
                Route::get('/chats/{uuid?}', [App\Http\Controllers\User\ChatController::class, 'index']);
                Route::get('/chats/{id}/media', [App\Http\Controllers\User\ChatController::class, 'getMedia']);
                Route::post('/chats', [App\Http\Controllers\User\ChatController::class, 'sendMessage']);
                Route::delete('/chats/{uuid}', [App\Http\Controllers\User\ChatController::class, 'deleteChats']);
                Route::get('/chat/send', [App\Http\Controllers\User\ChatController::class, 'sendMessage']);
                Route::post('/chat/{uuid}/send/template', [App\Http\Controllers\User\ChatController::class, 'sendTemplateMessage']);
                Route::get('/chat/test/{id}', [App\Http\Controllers\User\ChatController::class, 'sendAutoReply']);
                Route::post('/chats/update-sort-direction', [App\Http\Controllers\User\ChatController::class, 'updateChatSortDirection']);

                Route::get('/tickets/{status}', [App\Http\Controllers\User\ChatTicketController::class, 'index']);
                Route::put('/tickets/{uuid}/update', [App\Http\Controllers\User\ChatTicketController::class, 'update']);
                Route::put('/tickets/{uuid}/assign', [App\Http\Controllers\User\ChatTicketController::class, 'assign']);

                Route::get('/contacts/{uuid?}', [App\Http\Controllers\User\ContactController::class, 'index'])->name('contacts');
                Route::post('/contacts', [App\Http\Controllers\User\ContactController::class, 'store']);
                Route::post('/contacts/import', [App\Http\Controllers\User\ContactController::class, 'import']);
                Route::post('/contacts/{uuid}', [App\Http\Controllers\User\ContactController::class, 'update']);
                Route::put('/contacts/favorite/{uuid}', [App\Http\Controllers\User\ContactController::class, 'favorite']);
                Route::delete('/contacts', [App\Http\Controllers\User\ContactController::class, 'delete']);

                Route::get('/contact-groups/{uuid?}', [App\Http\Controllers\User\ContactGroupController::class, 'index']);
                Route::post('/contact-groups', [App\Http\Controllers\User\ContactGroupController::class, 'store']);
                Route::post('/contact-groups/import', [App\Http\Controllers\User\ContactGroupController::class, 'import']);
                Route::post('/contact-groups/{uuid}', [App\Http\Controllers\User\ContactGroupController::class, 'update']);
                Route::delete('/contact-groups', [App\Http\Controllers\User\ContactGroupController::class, 'delete']);

                Route::get('/contact-labels/{uuid?}', [App\Http\Controllers\User\ContactLabelController::class, 'index']);
                Route::post('/contact-labels', [App\Http\Controllers\User\ContactLabelController::class, 'store']);
                Route::post('/contact-labels/{uuid}', [App\Http\Controllers\User\ContactLabelController::class, 'update']);
                Route::delete('/contact-labels', [App\Http\Controllers\User\ContactLabelController::class, 'delete']);
                Route::post('/contact-labels/contact/store', [App\Http\Controllers\User\ContactLabelController::class, 'contactStore']);
                Route::post('/contact-labels/bulk_contact/store', [App\Http\Controllers\User\ContactLabelController::class, 'bulkContactStore']);

                Route::get('/campaigns/{uuid?}', [App\Http\Controllers\User\CampaignController::class, 'index'])->name('campaigns');
                Route::post('/campaigns', [App\Http\Controllers\User\CampaignController::class, 'store']);
                Route::get('/campaigns/export/{uuid?}', [App\Http\Controllers\User\CampaignController::class, 'export']);
                Route::delete('/campaigns/{uuid?}', [App\Http\Controllers\User\CampaignController::class, 'delete']);

                Route::match(['get', 'post'], '/templates/create', [App\Http\Controllers\User\TemplateController::class, 'create']);
                Route::get('/templates/{uuid?}', [App\Http\Controllers\User\TemplateController::class, 'index']);
                Route::post('/templates', [App\Http\Controllers\User\TemplateController::class, 'store']);
                Route::post('/templates/{uuid}', [App\Http\Controllers\User\TemplateController::class, 'update']);
                Route::delete('/templates/{uuid}', [App\Http\Controllers\User\TemplateController::class, 'delete']);

                Route::get('/automation/basic', [App\Http\Controllers\User\CannedReplyController::class, 'index'])->name('cannedReply');
                Route::get('/automation/basic/create', [App\Http\Controllers\User\CannedReplyController::class, 'create'])->name('cannedReply.create');
                Route::post('/automation/basic', [App\Http\Controllers\User\CannedReplyController::class, 'store'])->name('cannedReply.store');
                Route::get('/automation/basic/{uuid}/edit', [App\Http\Controllers\User\CannedReplyController::class, 'edit'])->name('cannedReply.edit');
                Route::put('/automation/basic/{uuid}', [App\Http\Controllers\User\CannedReplyController::class, 'update'])->name('cannedReply.update');
                Route::delete('/automation/basic/{uuid}', [App\Http\Controllers\User\CannedReplyController::class, 'delete'])->name('cannedReply.destroy');

                Route::get('/support/{uuid?}', [App\Http\Controllers\User\TicketController::class, 'index'])->name('support');
                Route::post('/support', [App\Http\Controllers\User\TicketController::class, 'store']);
                Route::post('/support/{uuid}/comment', [App\Http\Controllers\User\TicketController::class, 'comment']);
                Route::post('/support/{uuid}/status', [App\Http\Controllers\User\TicketController::class, 'changeStatus']);
                Route::post('/support/{uuid}/priority', [App\Http\Controllers\User\TicketController::class, 'changePriority']);

                Route::match(['get', 'post'], '/messages', [App\Http\Controllers\User\MessageController::class, 'index']);
                Route::match(['get', 'post'], '/message-templates', [App\Http\Controllers\User\TemplateController::class, 'index']);
                Route::match(['get', 'post'], '/instances', [App\Http\Controllers\User\InstanceController::class, 'index']);

                Route::get('/team', [App\Http\Controllers\User\TeamController::class, 'index'])->name('team');

                Route::group(['middleware' => 'check.client.role'], function () {
                    Route::get('/settings', [App\Http\Controllers\User\SettingController::class, 'index']);
                    Route::get('/settings/m', [App\Http\Controllers\User\SettingController::class, 'mobileView']);

                    Route::get('/settings/whatsapp', [App\Http\Controllers\User\SettingController::class, 'viewWhatsappSettings']);
                    Route::get('/settings/whatsapp/refresh', [App\Http\Controllers\User\SettingController::class, 'refreshWhatsappData']);
                    Route::post('/settings/whatsapp/token', [App\Http\Controllers\User\SettingController::class, 'updateToken']);
                    Route::post('/settings/whatsapp', [App\Http\Controllers\User\SettingController::class, 'storeWhatsappSettings']);
                    Route::post('/settings/whatsapp/business-profile', [App\Http\Controllers\User\SettingController::class, 'whatsappBusinessProfileUpdate']);
                    Route::delete('/settings/whatsapp/business-profile', [App\Http\Controllers\User\SettingController::class, 'deleteWhatsappIntegration']);
                    Route::match(['get', 'post'], '/settings/contacts', [App\Http\Controllers\User\SettingController::class, 'contacts']);
                    Route::match(['get', 'post'], '/settings/tickets', [App\Http\Controllers\User\SettingController::class, 'tickets']);
                    Route::match(['get', 'post'], '/settings/automation', [App\Http\Controllers\User\SettingController::class, 'automation']);
                    Route::resource('contact-fields', App\Http\Controllers\User\ContactFieldController::class);

                    Route::post('/team/invite', [App\Http\Controllers\User\TeamController::class, 'invite'])->name('team.store');
                    Route::put('/team/{uuid}', [App\Http\Controllers\User\TeamController::class, 'update'])->name('team.update');
                    Route::delete('/team/{uuid}', [App\Http\Controllers\User\TeamController::class, 'delete'])->name('team.destroy');

                    Route::get('/developer-tools/access-tokens', [App\Http\Controllers\User\DeveloperController::class, 'index']);
                    Route::post('/developer-tools/access-tokens', [App\Http\Controllers\User\DeveloperController::class, 'store']);
                    Route::delete('/developer-tools/access-tokens/{uuid}', [App\Http\Controllers\User\DeveloperController::class, 'delete']);

                    Route::get('/whatsapp/workflow', [App\Http\Controllers\WebhookWorkflowController::class, 'index'])->name('webhook.index');
                    Route::post('/whatsapp/workflow', [App\Http\Controllers\WebhookWorkflowController::class, 'store'])->name('webhook.store');
                    Route::delete('/whatsapp/workflow/{uuid}', [App\Http\Controllers\WebhookWorkflowController::class, 'delete'])->name('webhook.delete');
                    Route::post('/get/raw-data', [App\Http\Controllers\WebhookWorkflowController::class, 'getRawData'])->name('webhook.getRawData');
                    Route::post('/response/recapture', [App\Http\Controllers\WebhookWorkflowController::class, 'recapture'])->name('webhook.recapture');
                    Route::post('/status/update', [App\Http\Controllers\WebhookWorkflowController::class, 'statusUpdate'])->name('webhook.status.update');
                    Route::post('/workflow/store', [App\Http\Controllers\WebhookWorkflowController::class, 'workflowStore'])->name('webhook.workflow.store');
                    Route::get('/whatsapp/workflow/{uuid}', [App\Http\Controllers\WebhookWorkflowController::class, 'whatsappWorkflowReport'])->name('whatsapp.workflow.report');
                    Route::post('/workflow/dataformatter/store', [App\Http\Controllers\WebhookWorkflowController::class, 'whatsappDataFormatterStore'])->name('whatsapp.dataformatter.store');
                    Route::delete('/workflow/dataformatter/{uuid}', [App\Http\Controllers\WebhookWorkflowController::class, 'whatsappDataFormatterDelete'])->name('whatsapp.dataformatter.delete');
                });

                //Route::get('/whatsapp/message', [App\Http\Controllers\User\WhatsappController::class, 'sendMessage']);
                Route::resource('notes', App\Http\Controllers\User\ChatNoteController::class);
            });
        });
    });
    Route::get('/ecommerce/products', [App\Http\Controllers\User\EcommerceController::class, 'products'])->name('ecommerce.products');
    Route::get('/ecommerce/orders', [App\Http\Controllers\User\EcommerceController::class, 'orders'])->name('ecommerce.orders');
    Route::get('/ecommerce/settings', [App\Http\Controllers\User\EcommerceController::class, 'settings'])->name('ecommerce.settings');
    Route::get('/ecommerce/webhook', [App\Http\Controllers\User\EcommerceController::class, 'webhook'])->name('ecommerce.webhook');
    Route::post('/sync_products', [App\Http\Controllers\User\EcommerceController::class, 'syncProducts'])->name('ecommerce.sync.products');
    Route::post('/store/catalog', [App\Http\Controllers\User\EcommerceController::class, 'storeCatalog'])->name('store.catalog');
    Route::post('/remove/catalog', [App\Http\Controllers\User\EcommerceController::class, 'removeCatalog'])->name('remove.catalog');
    Route::post('/store/ecommerce/settings', [App\Http\Controllers\User\EcommerceController::class, 'storeEcommerceSettings'])->name('store.ecommerce.settings');
    Route::post('/store/whatsapp-pay/settings', [App\Http\Controllers\User\EcommerceController::class, 'storeWhatsAppPayment'])->name('store.whatsAppPayment');
    Route::post('/get/orderProducts', [App\Http\Controllers\User\EcommerceController::class, 'getOrderProducts'])->name('get.orderProducts');
    Route::post('/store/apiurl', [App\Http\Controllers\User\EcommerceController::class, 'storeApiURL'])->name('store.apiURL');
    Route::post('/store/ecommerce/webhook', [App\Http\Controllers\User\EcommerceController::class, 'storeEcommerceWebhook'])->name('store.ecommerce.webhook');
    Route::delete('/delete/ecommerce/webhook/{uuid}', [App\Http\Controllers\User\EcommerceController::class, 'deleteEcommerceWebhook'])->name('delete.ecommerce.webhook');
    Route::get('/tools', [App\Http\Controllers\User\ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/whatsapp-widget', [App\Http\Controllers\User\ToolController::class, 'whatsAppWidget'])->name('tools.whatsAppWidget');
    Route::post('/tools/save-whatsapp-widget', [App\Http\Controllers\User\ToolController::class, 'saveWhatsAppWidget'])->name('tools.saveWhatsAppWidget');
    Route::post('/tools/whatsapp-widget/upload-profile-image', [App\Http\Controllers\User\ToolController::class, 'uploadProfileImage'])->name('tools.uploadProfileImage');
});

//Admin Panel Routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('organizations', App\Http\Controllers\Admin\OrganizationController::class);
    /*Route::resource('blog/posts', App\Http\Controllers\Admin\BlogController::class);
    Route::resource('blog/categories', App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::resource('blog/authors', App\Http\Controllers\Admin\BlogAuthorController::class);
    Route::resource('blog/tags', App\Http\Controllers\Admin\BlogTagController::class);*/
    Route::resource('tax-rates', App\Http\Controllers\Admin\TaxController::class)->middleware('check.master.admin');
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class)->middleware('check.master.admin');
    Route::resource('faqs', App\Http\Controllers\Admin\FaqController::class)->middleware('check.master.admin');
    Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class)->middleware('check.master.admin');
    Route::resource('plans', App\Http\Controllers\Admin\SubscriptionPlanController::class);
    Route::resource('team/users', App\Http\Controllers\Admin\TeamController::class)->middleware('check.master.admin');
    Route::resource('team/roles', App\Http\Controllers\Admin\RoleController::class)->middleware('check.master.admin');
    Route::resource('billing', App\Http\Controllers\Admin\BillingController::class);
    Route::resource('addons', App\Http\Controllers\Admin\AddonController::class)->middleware('check.master.admin');
    Route::post('addons/install', [App\Http\Controllers\Admin\AddonController::class, 'install']);
    Route::post('addons/update', [App\Http\Controllers\Admin\AddonController::class, 'update']);
    Route::post('/addons/setup/google-recaptcha', [App\Http\Controllers\Admin\AddonController::class, 'store']);
    Route::post('/addons/setup/google-analytics', [App\Http\Controllers\Admin\AddonController::class, 'store']);
    Route::post('/addons/setup/google-maps', [App\Http\Controllers\Admin\AddonController::class, 'store']);
    Route::post('/addons/setup/google-authenticator', [App\Http\Controllers\Admin\AddonController::class, 'store']);
    Route::resource('payment-gateways', App\Http\Controllers\Admin\PaymentGatewayController::class)->only(['index', 'show', 'update'])->middleware('check.master.admin');
    Route::get('/languages/{language}/export', [App\Http\Controllers\Admin\LanguageController::class, 'export']);
    Route::post('/languages/{language}/import', [App\Http\Controllers\Admin\LanguageController::class, 'import']);
    Route::get('/languages/{language}/translations', [App\Http\Controllers\Admin\LanguageController::class, 'translations']);
    Route::get('/languages/{language}/default', [App\Http\Controllers\Admin\LanguageController::class, 'setDefault']);
    Route::resource('languages', App\Http\Controllers\Admin\LanguageController::class)->middleware('check.master.admin');
    Route::post('/translations/{languageCode}/{key}', [App\Http\Controllers\Admin\LanguageController::class, 'updateTranslation']);

    /*Route::get('/pages', [App\Http\Controllers\Admin\PageController::class, 'index']);
    Route::get('/users/{uuid}/organizations', [App\Http\Controllers\Admin\CustomerController::class, 'userOrganizations']);
    Route::get('/subscriptions', [App\Http\Controllers\Admin\SubscriptionController::class, 'index']);*/
    Route::get('/payment-logs', [App\Http\Controllers\Admin\PaymentController::class, 'index']);

    Route::get('/support/{uuid?}', [App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets')->middleware('check.master.admin');
    Route::post('/support', [App\Http\Controllers\Admin\TicketController::class, 'store']);
    Route::post('/support/{uuid}/comment', [App\Http\Controllers\Admin\TicketController::class, 'comment']);
    Route::post('/support/{uuid}/status', [App\Http\Controllers\Admin\TicketController::class, 'changeStatus']);
    Route::post('/support/{uuid}/priority', [App\Http\Controllers\Admin\TicketController::class, 'changePriority']);
    Route::post('/support/{uuid}/assign', [App\Http\Controllers\Admin\TicketController::class, 'assign']);

    Route::middleware(['check.master.admin'])->group(function () {
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index']);
        Route::match(['get', 'post'], '/settings/general', [App\Http\Controllers\Admin\SettingController::class, 'general']);
        Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update']);
        Route::get('/settings/smtp', [App\Http\Controllers\Admin\SettingController::class, 'email']);
        Route::get('/settings/broadcast-drivers', [App\Http\Controllers\Admin\SettingController::class, 'broadcast_driver']);
        Route::match(['get', 'post'], '/settings/timezone', [App\Http\Controllers\Admin\SettingController::class, 'timezone']);
        Route::get('/settings/email-templates', [App\Http\Controllers\Admin\EmailTemplateController::class, 'index']);
        Route::get('/settings/email-template/{id}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'show']);
        Route::put('/settings/email-template/{id}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'update']);
        Route::get('/settings/pages', [App\Http\Controllers\Admin\PagesController::class, 'index']);
        Route::post('/settings/pages', [App\Http\Controllers\Admin\PagesController::class, 'store']);
        Route::get('/settings/page/{id}', [App\Http\Controllers\Admin\PagesController::class, 'show']);
        Route::put('/settings/page/{id}', [App\Http\Controllers\Admin\PagesController::class, 'update']);
        Route::delete('/settings/page/{id}', [App\Http\Controllers\Admin\PagesController::class, 'delete']);
        Route::match(['get', 'post'], '/settings/billing', [App\Http\Controllers\Admin\SettingController::class, 'billing']);
        Route::get('/settings/storage', [App\Http\Controllers\Admin\SettingController::class, 'storage']);
        Route::get('/settings/socials', [App\Http\Controllers\Admin\SettingController::class, 'socials']);
        Route::get('/settings/subscription', [App\Http\Controllers\Admin\SettingController::class, 'subscription']);
    });

    Route::get('/updates', [App\Http\Controllers\Admin\UpdateController::class, 'index']);
    Route::get('/updates/check', [App\Http\Controllers\Admin\UpdateController::class, 'checkUpdate']);
    Route::post('/update', [App\Http\Controllers\Admin\UpdateController::class, 'update']);

    Route::get('/user-logs/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index']);
    Route::get('/user-logs/emails', [App\Http\Controllers\Admin\EmailLogController::class, 'index']);
});
