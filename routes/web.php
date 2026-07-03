<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContactDetailsController;
use App\Http\Controllers\ImportUserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;

use Illuminate\Support\Facades\Storage;

//for pdf
Route::get('/view-pdf/{fileName}', function ($fileName) {
    $filePath = Storage::path('documents/' . $fileName);
    return response()->file($filePath);
})->name('view.pdf');



//users

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/{user}/update-vacant-status', [UserController::class, 'updateVacantStatus'])->name('users.updateVacantStatus');

Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

Route::get('/admins/search', [AdminController::class, 'search'])->name('admins.search');
Route::resource('/admins', AdminController::class);



Route::get('/', function () {
    return view('auth.login');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes for usersetting
Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change.password');
Route::get('/contact-details', [ContactDetailsController::class, 'index'])->name('contact.details');

    // Other authenticated routes...

    Route::post('/change-password/update', [ChangePasswordController::class, 'update'])->name('change.password.update');
    Route::post('/contact-details/update', [ContactDetailsController::class, 'update'])->name('contact.details.update');

//userimport
Route::get('/import-form', [ImportUserController::class, 'importForm'])->name('import-form');
Route::post('/import', [ImportUserController::class, 'import'])->name('import');
Route::post('/import/row', [ImportUserController::class, 'importRow'])->name('import.row');

//properties import
Route::get('/property/import', [PropertyController::class, 'importPropertiesForm'])->name('property.import.form');
Route::post('/property/import', [PropertyController::class, 'importProperties'])->name('property.import');
Route::post('/property/import/row', [PropertyController::class, 'importPropertyRow'])->name('property.import.row');
Route::resource('/properties', PropertyController::class);

// HouseholdController

Route::get('/household/import-form', [HouseholdController::class, 'importForm'])->name('household.import.form');
Route::post('/household/import', [HouseholdController::class, 'import'])->name('household.import');
Route::post('/household/import/row', [HouseholdController::class, 'importHouseholdRow'])->name('household.import.row');

Route::get('/household', [HouseholdController::class, 'index'])->name('household.index');
Route::get('/household/create', [HouseholdController::class, 'create'])->name('household.create');
Route::post('/household/store', [HouseholdController::class, 'store'])->name('household.store');
Route::get('/household/{id}/edit', [HouseholdController::class, 'edit'])->name('household.edit');
Route::put('/household/{id}/update', [HouseholdController::class, 'update'])->name('household.update');
Route::delete('/household/{id}/destroy', [HouseholdController::class, 'destroy'])->name('household.destroy');
Route::get('/household/search', [HouseholdController::class, 'search'])->name('household.search');






// UserDashboard


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/upload-document', [UserDashboardController::class, 'uploadDocument'])->name('upload.document');
    Route::get('/user/rejected-documents', [UserDashboardController::class, 'getRejectedDocuments'])->name('user.rejected.documents');
});
Route::post('/reupload-document', [UserDashboardController::class, 'reuploadDocument'])->name('reupload.document');


// routes/web.php


Route::get('/user-dashboard/upload-document/{userId}', [UserDashboardController::class, 'uploadDocument'])
    ->name('user-dashboard.upload-document');
    
    Route::get('/document-status', [UserDashboardController::class, 'documentStatus'])->name('document.status');
    // Documents controller 
    
    use App\Http\Controllers\DocumentController;
    
    Route::get('/documents/{familyMemberId}', [DocumentController::class, 'index'])->name('documents.index');
    
    require __DIR__.'/auth.php';




// admin dashboard routes
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/show-user-documents/{family_member_id}', [AdminDashboardController::class, 'showUserDocuments'])->name('admin.showUserDocuments');


// Route::post('/admin/documents/approve/{documentId}', [AdminDashboardController::class, 'approveDocument'])
//     ->name('admin.approveDocument');

// Route::post('/admin/documents/reject/{documentId}', [AdminDashboardController::class, 'rejectDocument'])
//     ->name('admin.rejectDocument');

// Document approval/rejection routes
Route::post('/admin/approve-document/{documentId}', [AdminDashboardController::class, 'approveDocument'])->name('admin.approveDocument');
Route::post('/admin/reject-document/{documentId}', [AdminDashboardController::class, 'rejectDocument'])->name('admin.rejectDocument');

// Route::get('/admin/dashboard', function () {
//     return view('admin.dashboard');
// })->middleware(['auth', 'verified'])->name('admin.dashboard');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware(['auth:admin'])->name('admin.dashboard');
require __DIR__.'/adminauth.php';


//  document routes


Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');