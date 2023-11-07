<?php
// Adding upgrade account function to view funds before release
    Route::post('upgrade-account-pro', [UpgradeController::class, 'upgradeUserAccount'])->name('upgrade-account-pro');