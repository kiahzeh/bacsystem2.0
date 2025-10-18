<?php

use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\NotificationTestController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DashboardController;
use App\Models\PurchaseRequest;
use App\Models\User;
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

// Lightweight liveness endpoint (no auth, always 200)
Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

// Consolidation handled by DashboardController to align with dashboard form

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    Route::get('/monthly-report', [App\Http\Controllers\ReportController::class, 'generateMonthlyReport'])->name('reports.monthly');
    Route::get('/consolidation', [DashboardController::class, 'showConsolidated'])->name('consolidation.index');
    Route::post('/consolidation/store', [DashboardController::class, 'store'])->name('consolidation.store');


// Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('purchase-requests', PurchaseRequestController::class);
    Route::get('purchase-requests/{purchaseRequest}/timeline', [PurchaseRequestController::class, 'timeline'])->name('purchase-requests.timeline');
    Route::get('purchase-requests/{purchaseRequest}/export', [PurchaseRequestController::class, 'export'])->name('purchase-requests.export');
    Route::get('purchase-requests/{purchaseRequest}/complete', [PurchaseRequestController::class, 'showCompleteForm'])->name('purchase-requests.complete');
    Route::post('purchase-requests/{purchaseRequest}/complete', [PurchaseRequestController::class, 'complete'])->name('purchase-requests.complete.store');
    
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
    Route::get('/documents/{document}/view', [DocumentController::class, 'view'])
        ->name('documents.view');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
        ->name('documents.destroy')
        ->middleware('admin');
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])
        ->name('documents.approve')
        ->middleware('admin');
    Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])
        ->name('documents.reject')
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

    // Delete confirmation route
    Route::get('/purchase-requests/{purchaseRequest}/delete-confirm', [PurchaseRequestController::class, 'deleteConfirm'])->name('purchase-requests.delete-confirm');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('processes', App\Http\Controllers\ProcessController::class);
    Route::post('/processes/reorder', [App\Http\Controllers\ProcessController::class, 'reorder'])->name('processes.reorder');
    Route::get('/reports/monthly/export', [App\Http\Controllers\ReportController::class, 'exportMonthlyPurchaseRequests'])->name('reports.monthly.export');
});

Route::post('/purchase-requests/{purchaseRequest}/workflow/reorder-steps', [App\Http\Controllers\PurchaseRequestController::class, 'reorderWorkflowSteps'])->name('purchase-requests.workflow.reorder-steps');

Route::get('/search/pr', [App\Http\Controllers\PurchaseRequestController::class, 'ajaxSearch'])->name('search.pr');

// Test and debug notification routes (require auth and use typed User)
Route::middleware('auth')->group(function () {
    Route::get('/test-notification', function () {
        $user = User::findOrFail(auth()->id());
        $user->notify(new \App\Notifications\NewPurchaseRequestCreated(PurchaseRequest::first()));
        return redirect()->back()->with('success', 'Test notification sent!');
    })->name('test.notification');

    Route::get('/test-notification-api', function () {
        $user = User::findOrFail(auth()->id());
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
            'unreadCount' => $user->notifications()->whereNull('read_at')->count(),
            'totalCount' => $user->notifications()->count(),
        ]);
    })->name('test.notification.api');

    Route::get('/debug-notifications', function () {
        $user = User::findOrFail(auth()->id());
        $allNotifications = $user->notifications()->take(5)->get();
        $unreadNotifications = $user->notifications()->whereNull('read_at')->take(5)->get();
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'total_notifications' => $user->notifications()->count(),
            'unread_notifications' => $user->notifications()->whereNull('read_at')->count(),
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
});

// User search API
Route::get('/api/users/search', [App\Http\Controllers\UserController::class, 'search'])->name('api.users.search');

require __DIR__ . '/auth.php';
