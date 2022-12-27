<?php

use Newnet\Acl\Http\Controllers\Admin\ProfileController;
use Newnet\Acl\Http\Controllers\Admin\RoleController;
use Newnet\Acl\Http\Controllers\Admin\UserController;

Route::prefix(config('core.admin_prefix'))
    ->domain(config('core.admin_domain'))
    ->middleware(config('core.admin_middleware'))
    ->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('admin.role.index')->middleware('admin.can:role.index');
            Route::get('create', [RoleController::class, 'create'])->name('admin.role.create')->middleware('admin.can:role.create');
            Route::post('/', [RoleController::class, 'store'])->name('admin.role.store')->middleware('admin.can:role.create');
            Route::get('{id}/edit', [RoleController::class, 'edit'])->name('admin.role.edit')->middleware('admin.can:role.edit');
            Route::put('{id}', [RoleController::class, 'update'])->name('admin.role.update')->middleware('admin.can:role.edit');
            Route::delete('{id}', [RoleController::class, 'destroy'])->name('admin.role.destroy')->middleware('admin.can:role.destroy');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index')->middleware('admin.can:user.index');
            Route::get('create', [UserController::class, 'create'])->name('admin.user.create')->middleware('admin.can:user.create');
            Route::post('/', [UserController::class, 'store'])->name('admin.user.store')->middleware('admin.can:user.create');
            Route::get('{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit')->middleware('admin.can:user.edit');
            Route::put('{id}', [UserController::class, 'update'])->name('admin.user.update')->middleware('admin.can:user.edit');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('admin.user.destroy')->middleware('admin.can:user.destroy');
        });

        Route::prefix('profile')->group(function () {
            Route::get('', [ProfileController::class, 'index'])->name('admin.profile.index');
            Route::post('', [ProfileController::class, 'update'])->name('admin.profile.update');
        });
    });
