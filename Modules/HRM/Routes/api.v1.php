<?php

use Illuminate\Support\Facades\Route;
use Modules\HRM\Http\Controllers\Api\V1\ArrivalController;
use Modules\HRM\Http\Controllers\Api\V1\AttendanceLogController;
use Modules\HRM\Http\Controllers\Api\V1\AttendanceRapidUpdateController;
use Modules\HRM\Http\Controllers\Api\V1\AwardController;
use Modules\HRM\Http\Controllers\Api\V1\DailyAttendanceListController;
use Modules\HRM\Http\Controllers\Api\V1\HrmDepartmentController;
use Modules\HRM\Http\Controllers\Api\V1\DesignationController;
use Modules\HRM\Http\Controllers\Api\V1\ELCalculationController;
use Modules\HRM\Http\Controllers\Api\V1\ELPaymentController;
use Modules\HRM\Http\Controllers\Api\V1\EmployeeController;
use Modules\HRM\Http\Controllers\Api\V1\EmployeeTaxAdjustmentController;
use Modules\HRM\Http\Controllers\Api\V1\FinalSettlementController;
use Modules\HRM\Http\Controllers\Api\V1\GradeController;
use Modules\HRM\Http\Controllers\Api\V1\HolidayController;
use Modules\HRM\Http\Controllers\Api\V1\JobCardController;
use Modules\HRM\Http\Controllers\Api\V1\JobCardSummaryController;
use Modules\HRM\Http\Controllers\Api\V1\LeaveApplicationController;
use Modules\HRM\Http\Controllers\Api\V1\LeaveApplicationReportController;
use Modules\HRM\Http\Controllers\Api\V1\LeaveRegisterController;
use Modules\HRM\Http\Controllers\Api\V1\LeaveTypeController;
use Modules\HRM\Http\Controllers\Api\V1\NoticeController;
use Modules\HRM\Http\Controllers\Api\V1\OvertimeAdjustmentController;
use Modules\HRM\Http\Controllers\Api\V1\PaymentTypeController;
use Modules\HRM\Http\Controllers\Api\V1\PersonWiseAttendanceController;
use Modules\HRM\Http\Controllers\Api\V1\PromotionController;
use Modules\HRM\Http\Controllers\Api\V1\ResignAndLeftEmployeeController;
use Modules\HRM\Http\Controllers\Api\V1\SalaryAdjustmentController;
use Modules\HRM\Http\Controllers\Api\V1\SalaryAdjustmentReportController;
use Modules\HRM\Http\Controllers\Api\V1\SalaryAdvanceController;
use Modules\HRM\Http\Controllers\Api\V1\SalarySettlementController;
use Modules\HRM\Http\Controllers\Api\V1\SectionController;
use Modules\HRM\Http\Controllers\Api\V1\SectionWiseAttendanceController;
use Modules\HRM\Http\Controllers\Api\V1\ShiftAdjustmentController;
use Modules\HRM\Http\Controllers\Api\V1\ShiftController;
use Modules\HRM\Http\Controllers\Api\V1\SoftDeleteController;
use Modules\HRM\Http\Controllers\Api\V1\SubSectionController;
use Modules\HRM\Http\Controllers\Api\V1\VisitController;
use Modules\HRM\Http\Controllers\OrganogramController;

Route::get('trash/{resource}', [SoftDeleteController::class, 'allTrash']);

Route::apiResource('departments', HrmDepartmentController::class);
Route::get('department/trash', [HrmDepartmentController::class, 'allTrash']);
Route::get('department/restore/{id}', [HrmDepartmentController::class, 'restore']);
Route::delete('department/permanent-delete/{id}', [HrmDepartmentController::class, 'permanentDelete']);
Route::post('department/bulk-actions', [HrmDepartmentController::class, 'bulkAction']);

Route::apiResource('sections', SectionController::class);
Route::get('section/trash', [SectionController::class, 'allTrash']);
Route::get('section/restore/{id}', [SectionController::class, 'restore']);
Route::delete('section/permanent-delete/{id}', [SectionController::class, 'permanentDelete']);
Route::post('section/bulk-actions', [SectionController::class, 'bulkAction']);

Route::apiResource('subsections', SubSectionController::class);
Route::get('subsection/trash', [SubSectionController::class, 'allTrash']);
Route::get('subsection/restore/{id}', [SubSectionController::class, 'restore']);
Route::delete('subsection/permanent-delete/{id}', [SubSectionController::class, 'permanentDelete']);
Route::post('subsection/bulk-actions', [SubSectionController::class, 'bulkAction']);

Route::apiResource('grades', GradeController::class);
Route::get('grade/trash', [GradeController::class, 'allTrash']);
Route::get('grade/restore/{id}', [GradeController::class, 'restore']);
Route::delete('grade/permanent-delete/{id}', [GradeController::class, 'permanentDelete']);
Route::post('grade/bulk-actions', [GradeController::class, 'bulkAction']);

Route::apiResource('holidays', HolidayController::class);
Route::get('holiday/trash', [HolidayController::class, 'allTrash']);
Route::get('holiday/restore/{id}', [HolidayController::class, 'restore']);
Route::delete('holiday/permanent-delete/{id}', [HolidayController::class, 'permanentDelete']);
Route::post('holiday/bulk-actions', [HolidayController::class, 'bulkAction']);

Route::apiResource('shifts', ShiftController::class);
Route::get('shift/trash', [ShiftController::class, 'allTrash']);
Route::get('shift/restore/{id}', [ShiftController::class, 'restore']);
Route::delete('shift/permanent-delete/{id}', [ShiftController::class, 'permanentDelete']);
Route::post('shift/bulk-actions', [ShiftController::class, 'bulkAction']);

Route::apiResource('designations', DesignationController::class);
Route::get('designation/trash/all', [DesignationController::class, 'allTrash']);
Route::get('designation/restore/{id}', [DesignationController::class, 'restore']);
Route::delete('designation/permanent-delete/{id}', [DesignationController::class, 'permanentDelete']);
Route::post('designation/bulk-actions', [DesignationController::class, 'bulkAction']);

Route::apiResource('leave-types', LeaveTypeController::class);
Route::get('leave-type/trash', [LeaveTypeController::class, 'allTrash']);
Route::get('leave-type/restore/{id}', [LeaveTypeController::class, 'restore']);
Route::delete('leave-type/permanent-delete/{id}', [LeaveTypeController::class, 'permanentDelete']);
Route::post('leave-type/bulk-actions', [LeaveTypeController::class, 'bulkAction']);
// leave- application routes
Route::get('leave-application/trash', [LeaveApplicationController::class, 'allTrash']);
Route::get('leave-application/restore/{id}', [LeaveApplicationController::class, 'restore']);
Route::delete('leave-application/permanent-delete/{id}', [LeaveApplicationController::class, 'permanentDelete']);
Route::post('leave-application/bulk-actions', [LeaveApplicationController::class, 'bulkAction']);
Route::apiResource('leave-applications', LeaveApplicationController::class);
// leave- application-Reports Filter routes
Route::get('leave-applications-report', [LeaveApplicationReportController::class, 'filter']);

// EL- Payment routes
Route::get('el-payments/trash', [ELPaymentController::class, 'allTrash']);
Route::get('el-payments/restore/{id}', [ELPaymentController::class, 'restore']);
Route::delete('el-payments/permanent-delete/{id}', [ELPaymentController::class, 'permanentDelete']);
Route::post('el-payments/bulk-actions', [ELPaymentController::class, 'bulkAction']);
Route::apiResource('el-payments', ELPaymentController::class);

// Payment Type routes
Route::get('payment-types/trash', [PaymentTypeController::class, 'allTrash']);
Route::get('payment-types/restore/{id}', [PaymentTypeController::class, 'restore']);
Route::delete('payment-types/permanent-delete/{id}', [PaymentTypeController::class, 'permanentDelete']);
Route::post('payment-types/bulk-actions', [PaymentTypeController::class, 'bulkAction']);
Route::apiResource('payment-types', PaymentTypeController::class);

Route::apiResource('shift-adjustments', ShiftAdjustmentController::class);
Route::get('shift-adjustment/trash', [ShiftAdjustmentController::class, 'allTrash']);
Route::get('shift-adjustment/restore/{id}', [ShiftAdjustmentController::class, 'restore']);
Route::delete('shift-adjustment/permanent-delete/{id}', [ShiftAdjustmentController::class, 'permanentDelete']);
Route::post('shift-adjustment/bulk-actions', [ShiftAdjustmentController::class, 'bulkAction']);

Route::get('organogram/data', [OrganogramController::class, 'data'])->name('organogram.data');

Route::apiResource('employees', EmployeeController::class);

Route::prefix('employee')->group(function () {
    Route::get('trashed-employees', [EmployeeController::class, 'trashIndex'])->name('employee.trashed');
    Route::get('resign-employees/', [ResignAndLeftEmployeeController::class, 'resignIndex'])->name('resign-employees.index');
    Route::get('left-employees/', [ResignAndLeftEmployeeController::class, 'leftIndex'])->name('left-employees.index');
    Route::get('active/{id}', [ResignAndLeftEmployeeController::class, 'employeeActive'])->name('employee.active');
    Route::post('manage/{id}', [ResignAndLeftEmployeeController::class, 'manageEmployee'])->name('employee.manage');
    Route::get('restore/{id}', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::delete('permanent-delete/{id}', [EmployeeController::class, 'permanentDelete'])->name('employees.permanent-delete');
    Route::post('bulk-actions', [EmployeeController::class, 'bulkAction'])->name('employees.bulk-action');

    /* shift change route */
    Route::get('shift/changes', [ShiftController::class, 'shiftChange']);
    Route::get('shift/change/{id}/{employee_id}', [ShiftController::class, 'shiftChangeById']);

    /* promotion route */
    Route::post('promotion/bulk-actions', [PromotionController::class, 'bulkAction']);
    Route::get('promotion/restore/{id}', [PromotionController::class, 'restore']);
    Route::delete('promotion/permanent/delete/{id}', [PromotionController::class, 'permanentDelete']);
    Route::get('promotion/trash/index', [PromotionController::class, 'trashIndex']);
    Route::resource('promotions', PromotionController::class);
    Route::get('arrivals', [ArrivalController::class, 'index']);
});

// awards routes
Route::get('awards/trash', [AwardController::class, 'allTrash']);
Route::get('awards/restore/{id}', [AwardController::class, 'restore']);
Route::delete('awards/permanent-delete/{id}', [AwardController::class, 'permanentDelete']);
Route::post('awards/bulk-actions', [AwardController::class, 'bulkAction']);
Route::apiResource('awards', AwardController::class);

// salary settlements routes
Route::prefix('salary')->group(function () {
    Route::controller(SalarySettlementController::class)->group(function () {
        Route::get('/salary-settlements', 'index')->name('salary_settlements.index');
        Route::post('/salary-settlements', 'store')->name('salary_settlements.store');
        Route::get('/salary-settlements/{id}', 'show')->name('salary_settlements.show');
        Route::get('/delete/last/settlement/{id}', 'deleteLastSettlement')->name('delete.last.settlement');
        Route::post('/bulk-actions', 'bulkAction')->name('settlements.bulk_action');
        Route::post('/department-wise/store', 'departmentWiseStore')->name('settlements.department_wise.store');
    });
});

// Salary adjustments
Route::get('salaryAdjustments/trash', [SalaryAdjustmentController::class, 'allTrash']);
Route::get('salaryAdjustments/restore/{id}', [SalaryAdjustmentController::class, 'restore']);
Route::delete('salaryAdjustments/permanent-delete/{id}', [SalaryAdjustmentController::class, 'permanentDelete']);
Route::post('salaryAdjustments/bulk-actions', [SalaryAdjustmentController::class, 'bulkAction']);
Route::apiResource('salaryAdjustments', SalaryAdjustmentController::class);
Route::get('salary-adjustment-report', [SalaryAdjustmentReportController::class, 'filter']);

// Overtime adjustments
Route::get('overtime-adjustments/trash', [OvertimeAdjustmentController::class, 'allTrash']);
Route::get('overtime-adjustments/restore/{id}', [OvertimeAdjustmentController::class, 'restore']);
Route::delete('overtime-adjustments/permanent-delete/{id}', [OvertimeAdjustmentController::class, 'permanentDelete']);
Route::post('overtime-adjustments/bulk-actions', [OvertimeAdjustmentController::class, 'bulkAction']);
Route::apiResource('overtime-adjustments', OvertimeAdjustmentController::class);

// Employee tax adjustments
Route::get('employee-tax-adjustments/trash', [EmployeeTaxAdjustmentController::class, 'allTrash']);
Route::get('employee-tax-adjustments/restore/{id}', [EmployeeTaxAdjustmentController::class, 'restore']);
Route::delete('employee-tax-adjustments/permanent-delete/{id}', [EmployeeTaxAdjustmentController::class, 'permanentDelete']);
Route::post('employee-tax-adjustments/bulk-actions', [EmployeeTaxAdjustmentController::class, 'bulkAction']);
Route::apiResource('employee-tax-adjustments', EmployeeTaxAdjustmentController::class);

// Notice routes
Route::get('notices/trash', [NoticeController::class, 'allTrash']);
Route::get('notices/restore/{id}', [NoticeController::class, 'restore']);
Route::delete('notices/permanent-delete/{id}', [NoticeController::class, 'permanentDelete']);
Route::post('notices/bulk-actions', [NoticeController::class, 'bulkAction']);
Route::resource('notices', NoticeController::class);

// Salary advance routes
Route::get('salary-advances/trash', [SalaryAdvanceController::class, 'allTrash']);
Route::get('salary-advances/restore/{id}', [SalaryAdvanceController::class, 'restore']);
Route::delete('salary-advances/permanent-delete/{id}', [SalaryAdvanceController::class, 'permanentDelete']);
Route::post('salary-advances/bulk-actions', [SalaryAdvanceController::class, 'bulkAction']);
Route::resource('salary-advances', SalaryAdvanceController::class);

// Visit Travel
Route::resource('visit', VisitController::class);
Route::get('visit/trash/all', [VisitController::class, 'allTrash'])->name('allTrash');
Route::post('visit/bulk-actions', [VisitController::class, 'bulkAction'])->name('visit.bulk-action');
Route::post('visit/show', [VisitController::class, 'bulkAction'])->name('visit.view');
Route::get('visit/restore/{id}', [VisitController::class, 'restore'])->name('visit.restore');
Route::delete('visit/permanent/delete/{id}', [VisitController::class, 'permanentDelete'])->name('visit.permanent-delete');

// Attendance
Route::resource('person-wise-attendance', PersonWiseAttendanceController::class);
Route::get('attendance-log', [AttendanceLogController::class, 'index'])->name('attendance.log');
Route::get('daily-attendance', [DailyAttendanceListController::class, 'index'])->name('attendance.list');
Route::get('employee-wise-rapid-update', [AttendanceRapidUpdateController::class, 'employeeWiseRapidUpdate'])->name('attendance.employeeWiseRapidUpdate');
Route::get('date-wise-rapid-update', [AttendanceRapidUpdateController::class, 'dateWiseRapidUpdate'])->name('attendance.dateWiseRapidUpdate');

// Section wise Attendance
Route::resource('section-wise-attendance', SectionWiseAttendanceController::class);
Route::get('show/section-wise-attendance', [SectionWiseAttendanceController::class, 'getAttendanceBySectionAndDate'])->name('section-wise.create_row');

// Leave Register
Route::get('leave-register', [LeaveRegisterController::class, 'leaveRegisterReport'])->name('leave_register');
// EL Calculation
Route::get('el-calculation', [ELCalculationController::class, 'index'])->name('el_calculation');
Route::get('job-card-print', [JobCardController::class, 'jobCardPrint'])->name('job_card_print');
Route::get('job-card-summery-print', [JobCardSummaryController::class, 'jobCardSummaryPrint'])->name('job_card_summary_print');

Route::get('final-settlement-paper', [FinalSettlementController::class, 'index'])->name('final_settlement.paper');
