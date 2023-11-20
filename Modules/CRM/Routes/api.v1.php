<?php

use Modules\CRM\Http\Controllers\Api\V1\AppointmentController;
use Modules\CRM\Http\Controllers\Api\V1\BusinessLeadController;
use Modules\CRM\Http\Controllers\Api\V1\IndividualLeadController;
use Modules\CRM\Http\Controllers\Api\V1\LeadController;

Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('business-leads', BusinessLeadController::class);
Route::apiResource('individual-leads', IndividualLeadController::class);
Route::apiResource('leads', LeadController::class);
