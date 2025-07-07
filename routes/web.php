<?php

use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\NotificationTestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Models\PurchaseRequest;
use App\Http\Controllers\ConsolidatedRequestController;
use App\Http\Controllers\ConsolidateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

// In routes/web.php

// Consolidation Routes


// General Routes
Route::get('/', function () {
    return view('welcome');
});

Route::post('/consolidation/store', [ConsolidateController::class, 'store'])->name('consolidation.store');
Route::get('/consolidation', [ConsolidateController::class, 'index'])->name('consolidation.index');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    Route::get('/monthly-report', [ReportController::class, 'generateMonthlyReport'])->name('reports.monthly');
    Route::get('/consolidation', [DashboardController::class, 'showConsolidated'])->name('consolidation.index');
    Route::post('/consolidation/store', [DashboardController::class, 'store'])->name('consolidation.store');


// Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('purchase-requests', PurchaseRequestController::class);
    Route::get('purchase-requests/{purchaseRequest}/timeline', [PurchaseRequestController::class, 'timeline'])->name('purchase-requests.timeline');
    
    // Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');

    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('departments', DepartmentController::class);
    });

    // Test Routes
    Route::get('/test-notifications', [NotificationTestController::class, 'showTestForm'])->name('test.notifications');
    Route::post('/test-email', [NotificationTestController::class, 'sendTestEmail'])->name('test.email');

    // Document routes
    Route::post('/purchase-requests/{purchaseRequest}/upload-document', [PurchaseRequestController::class, 'uploadDocument'])
        ->name('purchase-requests.upload-document');
    Route::put('/purchase-requests/{purchaseRequest}/update-steps', [PurchaseRequestController::class, 'updateSteps'])
        ->name('purchase-requests.update-steps');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
        ->name('documents.destroy')
        ->middleware('admin');

    // Purchase Request routes
    Route::get('purchase-requests/competitive-list', [PurchaseRequestController::class, 'competitiveList'])->name('purchase-requests.competitive-list');

    // Workflow routes
    Route::post('/purchase-requests/{purchaseRequest}/workflow/add-step', [PurchaseRequestController::class, 'addWorkflowStep'])->name('purchase-requests.workflow.add-step');
    Route::get('/purchase-requests/{purchaseRequest}/workflow/available-processes', [PurchaseRequestController::class, 'getAvailableProcesses'])->name('purchase-requests.workflow.available-processes');
    Route::get('/purchase-requests/{purchaseRequest}/workflow/remove-step/{stepIndex}/confirm', [PurchaseRequestController::class, 'confirmRemoveWorkflowStep'])->name('purchase-requests.workflow.remove-step.confirm');
    Route::delete('/purchase-requests/{purchaseRequest}/workflow/remove-step/{stepIndex}', [PurchaseRequestController::class, 'removeWorkflowStep'])->name('purchase-requests.workflow.remove-step');
    Route::get('/purchase-requests/{purchaseRequest}/workflow/next-step/{stepIndex}/confirm', [PurchaseRequestController::class, 'confirmNextWorkflowStep'])->name('purchase-requests.workflow.next-step.confirm');
    Route::get('/purchase-requests/{purchaseRequest}/workflow/skip-step/{stepIndex}/confirm', [PurchaseRequestController::class, 'confirmSkipWorkflowStep'])->name('purchase-requests.workflow.skip-step.confirm');
    Route::get('/purchase-requests/{purchaseRequest}/workflow/reset-to-default/confirm', [PurchaseRequestController::class, 'confirmResetWorkflowToDefault'])->name('purchase-requests.workflow.reset-to-default.confirm');
    Route::patch('/purchase-requests/{purchaseRequest}/workflow/skip-step/{stepIndex}', [PurchaseRequestController::class, 'skipWorkflowStep'])->name('purchase-requests.workflow.skip-step');
    Route::patch('/purchase-requests/{purchaseRequest}/workflow/next-step/{stepIndex}', [PurchaseRequestController::class, 'nextWorkflowStep'])->name('purchase-requests.workflow.next-step');
    Route::patch('/purchase-requests/{purchaseRequest}/workflow/reset-to-default', [PurchaseRequestController::class, 'resetWorkflowToDefault'])->name('purchase-requests.workflow.reset-to-default');

    // Purchase Request type conversion
    Route::patch('/purchase-requests/{purchaseRequest}/convert-type', [PurchaseRequestController::class, 'convertType'])->name('purchase-requests.convert-type');

    // Delete confirmation route
    Route::get('/purchase-requests/{purchaseRequest}/delete-confirm', [PurchaseRequestController::class, 'deleteConfirm'])->name('purchase-requests.delete-confirm');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('processes', App\Http\Controllers\ProcessController::class);
    Route::get('/reports/monthly/export', [App\Http\Controllers\ReportController::class, 'exportMonthlyPurchaseRequests'])->name('reports.monthly.export');
});

Route::get('/search/pr', [App\Http\Controllers\PurchaseRequestController::class, 'ajaxSearch'])->name('search.pr');

// Test notification route
Route::get('/test-notification', function () {
    $user = auth()->user();
    $user->notify(new \App\Notifications\NewPurchaseRequestCreated(\App\Models\PurchaseRequest::first()));
    return redirect()->back()->with('success', 'Test notification sent!');
})->name('test.notification');

// Test notification API route
Route::get('/test-notification-api', function () {
    $user = auth()->user();
    $notifications = $user->notifications()->take(10)->get()->map(function ($notification) {
        return [
            'id' => $notification->id,
            'message' => $notification->data['message'] ?? 'Notification',
            'created_at' => $notification->created_at->diffForHumans(),
            'read' => !is_null($notification->read_at),
            'action_url' => $notification->data['action_url'] ?? null,
        ];
    });
    
    return response()->json([
        'notifications' => $notifications,
        'unreadCount' => $user->unreadNotifications()->count(),
        'totalCount' => $user->notifications()->count(),
    ]);
})->name('test.notification.api');

// Debug notifications route
Route::get('/debug-notifications', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'No authenticated user'], 401);
    }
    
    $allNotifications = $user->notifications()->take(5)->get();
    $unreadNotifications = $user->unreadNotifications()->take(5)->get();
    
    return response()->json([
        'user_id' => $user->id,
        'user_name' => $user->name,
        'total_notifications' => $user->notifications()->count(),
        'unread_notifications' => $user->unreadNotifications()->count(),
        'all_notifications' => $allNotifications->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'data' => $n->data,
                'read_at' => $n->read_at,
                'created_at' => $n->created_at->toISOString(),
            ];
        }),
        'unread_notifications' => $unreadNotifications->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'data' => $n->data,
                'read_at' => $n->read_at,
                'created_at' => $n->created_at->toISOString(),
            ];
        }),
    ]);
})->name('debug.notifications');

require __DIR__ . '/auth.php';
