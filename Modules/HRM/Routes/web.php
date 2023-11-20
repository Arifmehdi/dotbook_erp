<?php

use Illuminate\Support\Facades\Route;
use Modules\HRM\Http\Controllers\AppointmentLetterController;
use Modules\HRM\Http\Controllers\ArrivalController;
use Modules\HRM\Http\Controllers\Attendance\AbsentAttendanceCheckController;
use Modules\HRM\Http\Controllers\Attendance\AttendanceController;
use Modules\HRM\Http\Controllers\Attendance\AttendanceLogController;
use Modules\HRM\Http\Controllers\Attendance\AttendanceRapidUpdateController;
use Modules\HRM\Http\Controllers\Attendance\BulkAttendanceImportController;
use Modules\HRM\Http\Controllers\Attendance\DailyAttendanceListController;
use Modules\HRM\Http\Controllers\Attendance\DateRangeAbsenceCheckerController;
use Modules\HRM\Http\Controllers\Attendance\PersonWiseAttendanceController;
use Modules\HRM\Http\Controllers\Attendance\SectionWiseAttendanceController;
use Modules\HRM\Http\Controllers\AwardController;
use Modules\HRM\Http\Controllers\CalculationCheckerController;
use Modules\HRM\Http\Controllers\CalendarController;
use Modules\HRM\Http\Controllers\HrmDepartmentController;
use Modules\HRM\Http\Controllers\DesignationController;
use Modules\HRM\Http\Controllers\ELCalculationController;
use Modules\HRM\Http\Controllers\ELPaymentController;
use Modules\HRM\Http\Controllers\EmployeeController;
use Modules\HRM\Http\Controllers\EmployeeTaxAdjustmentController;
use Modules\HRM\Http\Controllers\FinalSettlementController;
use Modules\HRM\Http\Controllers\GradeController;
use Modules\HRM\Http\Controllers\HolidayController;
use Modules\HRM\Http\Controllers\HRMDashboardController;
use Modules\HRM\Http\Controllers\IdCardController;
use Modules\HRM\Http\Controllers\ImportEmployeeController;
use Modules\HRM\Http\Controllers\JobCardController;
use Modules\HRM\Http\Controllers\JobCardSummaryController;
use Modules\HRM\Http\Controllers\LeaveApplicationController;
use Modules\HRM\Http\Controllers\LeaveApplicationReportController;
use Modules\HRM\Http\Controllers\LeaveRegisterController;
use Modules\HRM\Http\Controllers\LeaveTypeController;
use Modules\HRM\Http\Controllers\MissingAttendanceController;
use Modules\HRM\Http\Controllers\NoticeController;
use Modules\HRM\Http\Controllers\OrganogramController;
use Modules\HRM\Http\Controllers\OvertimeAdjustmentController;
use Modules\HRM\Http\Controllers\PaymentTypeController;
use Modules\HRM\Http\Controllers\PromotionController;
use Modules\HRM\Http\Controllers\Recruitments\ConvertToEmployeeController;
use Modules\HRM\Http\Controllers\Recruitments\InterviewController;
use Modules\HRM\Http\Controllers\Recruitments\InterviewQuestionController;
use Modules\HRM\Http\Controllers\Recruitments\InterviewScheduleController;
use Modules\HRM\Http\Controllers\Recruitments\RecruitmentController;
use Modules\HRM\Http\Controllers\ResignAndLeftEmployeeController;
use Modules\HRM\Http\Controllers\SalaryAdjustmentReportController;
use Modules\HRM\Http\Controllers\SalaryAdjustmentsController;
use Modules\HRM\Http\Controllers\SalaryAdvanceController;
use Modules\HRM\Http\Controllers\SalaryListController;
use Modules\HRM\Http\Controllers\SalarySettlementController;
use Modules\HRM\Http\Controllers\SectionController;
use Modules\HRM\Http\Controllers\SettingController;
use Modules\HRM\Http\Controllers\ShiftAdjustmentController;
use Modules\HRM\Http\Controllers\ShiftController;
use Modules\HRM\Http\Controllers\SubSectionController;
use Modules\HRM\Http\Controllers\VisitController;

//Settings Routes.

Route::group(['prefix' => ''], function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    // Route::post('id-card/settings', [SettingController::class, 'idCardSettings'])->name('idcard.settings');
    Route::post('idCard/settings', [SettingController::class, 'updateCardSetting'])->name('store.card.settings');
    Route::post('payrolls/settings', [SettingController::class, 'updatePayrollSetting'])->name('store.payroll.settings');
});

Route::post('generals/settings', [SettingController::class, 'updateGeneralSetting'])->name('store.general.settings');
Route::post('colors/settings', [SettingController::class, 'updateColorSetting'])->name('store.color.settings');
Route::post('idCard/settings', [SettingController::class, 'updateCardSetting'])->name('store.card.settings');
Route::post('payrolls/settings', [SettingController::class, 'updatePayrollSetting'])->name('store.payroll.settings');

// HrmDepartment Section Routes.
Route::prefix('manage-departments')->group(function () {
    Route::resource('departments', HrmDepartmentController::class);
    Route::get('department/restore/{id}', [HrmDepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('department/permanent-delete/{id}', [HrmDepartmentController::class, 'permanentDelete'])->name('departments.permanent-delete');
    Route::post('department/bulk-actions', [HrmDepartmentController::class, 'bulkAction'])->name('departments.bulk-action');

    Route::resource('shifts', ShiftController::class);
    Route::get('shift/restore/{id}', [ShiftController::class, 'restore'])->name('shifts.restore');
    Route::delete('shift/permanent-delete/{id}', [ShiftController::class, 'permanentDelete'])->name('shifts.permanent-delete');
    Route::post('shift/bulk-actions', [ShiftController::class, 'bulkAction'])->name('shifts.bulk-action');
    Route::resource('shift-adjustments', ShiftAdjustmentController::class);
    Route::get('shift-adjustment/restore/{id}', [ShiftAdjustmentController::class, 'restore'])->name('shift-adjustments.restore');
    Route::delete('shift-adjustment/permanent-delete/{id}', [ShiftAdjustmentController::class, 'permanentDelete'])->name('shift-adjustments.permanent-delete');
    Route::post('shift-adjustment/bulk-actions', [ShiftAdjustmentController::class, 'bulkAction'])->name('shift-adjustments.bulk-action');

    Route::resource('grades', GradeController::class);
    Route::get('grade/restore/{id}', [GradeController::class, 'restore'])->name('grades.restore');
    Route::delete('grade/permanent-delete/{id}', [GradeController::class, 'permanentDelete'])->name('grades.permanent-delete');
    Route::post('grade/bulk-actions', [GradeController::class, 'bulkAction'])->name('grades.bulk-action');

    Route::resource('sections', SectionController::class);
    Route::get('section/restore/{id}', [SectionController::class, 'restore'])->name('sections.restore');
    Route::delete('section/permanent-delete/{id}', [SectionController::class, 'permanentDelete'])->name('sections.permanent-delete');
    Route::post('section/bulk-actions', [SectionController::class, 'bulkAction'])->name('sections.bulk-action');

    Route::resource('subsections', SubSectionController::class);
    Route::post('subsection/pluck', [SubSectionController::class, 'getSubsectionDoPluck'])->name('subsection.pluck');
    Route::get('subsection/restore/{id}', [SubSectionController::class, 'restore'])->name('subsections.restore');
    Route::delete('subsection/permanent-delete/{id}', [SubSectionController::class, 'permanentDelete'])->name('subsections.permanent-delete');
    Route::post('subsection/bulk-actions', [SubSectionController::class, 'bulkAction'])->name('subsections.bulk-action');

    Route::resource('designations', DesignationController::class);
    Route::get('designation/restore/{id}', [DesignationController::class, 'restore'])->name('designations.restore');
    Route::delete('designation/permanent-delete/{id}', [DesignationController::class, 'permanentDelete'])->name('designations.permanent-delete');
    Route::post('designation/bulk-actions', [DesignationController::class, 'bulkAction'])->name('designations.bulk-action');

    Route::get('organogram/', [OrganogramController::class, 'index'])->name('organogram.index');
    Route::get('organogram/data', [OrganogramController::class, 'data'])->name('organogram.data');
});

//Get Item By Parents Routes(For DropDown Uses).
Route::get('/get-section-by-department/{id}', [SectionController::class, 'getSectionByHrmDepartment']);
Route::get('/get-sub-section-by-section/{id}', [SubSectionController::class, 'getSubSectionBySection']);
Route::get('/get-designation-by-section/{id}', [DesignationController::class, 'getDesignationBySection']);

//Leaves Section Routes
Route::prefix('leaves')->group(function () {
    Route::resource('leave-types', LeaveTypeController::class);
    Route::get('leave-type/restore/{id}', [LeaveTypeController::class, 'restore'])->name('leave-types.restore');
    Route::delete('leave-type/permanent-delete/{id}', [LeaveTypeController::class, 'permanentDelete'])->name('leave-types.permanent-delete');
    Route::post('leave-type/bulk-actions', [LeaveTypeController::class, 'bulkAction'])->name('leave-types.bulk-action');
    // Leave Register
    Route::get('leave-register', [LeaveRegisterController::class, 'leaveRegister'])->name('leave_register');
    Route::get('leave-report-print', [LeaveRegisterController::class, 'leaveReportPrint'])->name('leave_report_print');
    // Holiday Routes
    Route::resource('holidays', HolidayController::class);
    Route::post('holiday/bulk-actions', [HolidayController::class, 'bulkAction'])->name('holidays.bulk-action');

    Route::get('holiday/restore/{id}', [HolidayController::class, 'restore'])->name('holidays.restore');
    Route::delete('holiday/permanent-delete/{id}', [HolidayController::class, 'permanentDelete'])->name('holidays.permanent-delete');
    // Route::get('calendar/event/store', [HolidayController::class, 'storeEvent'])->name('holidays.storeEvent');

    // leave Application Routes
    Route::resource('leave-applications', LeaveApplicationController::class);
    Route::get('leave-attachment/{id}',[LeaveApplicationController::class,'leaveAttachment'])->name('leave_application.show');

    Route::post('leave-applications/bulk-actions', [LeaveApplicationController::class, 'bulkAction'])->name('leave-applications.bulk-action');

    Route::get('leave-applications/restore/{id}', [LeaveApplicationController::class, 'restore'])->name('leave-applications.restore');
    Route::delete('leave-applications/permanent-delete/{id}', [LeaveApplicationController::class, 'permanentDelete'])->name('leave-applications.permanent-delete');
    // holiday calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/store', [CalendarController::class, 'store'])->name('calendar.store');
    Route::patch('/calendar/update/{id}', [CalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/destroy/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // EL Payment Routes
    Route::resource('el-payments', ELPaymentController::class);
    Route::post('el-payments/bulk-actions', [ELPaymentController::class, 'bulkAction'])->name('el-payments.bulk-action');

    Route::get('el-payments/restore/{id}', [ELPaymentController::class, 'restore'])->name('el-payments.restore');
    Route::delete('el-payments/permanent-delete/{id}', [ELPaymentController::class, 'permanentDelete'])->name('el-payments.permanent-delete');

    // hrm Payment type Routes
    Route::resource('payment-types', PaymentTypeController::class);
    Route::post('payment-types/bulk-actions', [PaymentTypeController::class, 'bulkAction'])->name('payment-types.bulk-action');

    Route::get('payment-types/restore/{id}', [PaymentTypeController::class, 'restore'])->name('payment-types.restore');
    Route::delete('payment-types/permanent-delete/{id}', [PaymentTypeController::class, 'permanentDelete'])->name('payment-types.permanent-delete');

    // hrm El Calcultion Routes
    Route::get('el-calculation', [ELCalculationController::class, 'index'])->name('el-calculation.index');
});

//Employee Section Routes
Route::resource('employees', EmployeeController::class);
Route::get('employee/excel', [EmployeeController::class, 'employeeExport'])->name('employee_list.excel');
Route::prefix('employee')->group(function () {
    Route::get('trashed-employees', [EmployeeController::class, 'trashIndex'])->name('employee.trashed');
    Route::get('view/{id}', [EmployeeController::class, 'employeeView'])->name('employee.view');
    Route::get('id/card/{id}', [EmployeeController::class, 'idCard'])->name('employee.id.card');
    Route::post('idcard-print-count', [EmployeeController::class, 'printCount'])->name('employee.id.card.print_count');
    //Employee List Route
    Route::get('active-employees-id', [EmployeeController::class, 'activeEmployeeListWithId'])->name('employee.active_list_with_id');
    Route::get('active-employees', [EmployeeController::class, 'activeEmployeeList'])->name('employee.active_list');

    Route::get('left/{id}', [ResignAndLeftEmployeeController::class, 'left'])->name('employee.left');
    Route::get('resign/{id}', [ResignAndLeftEmployeeController::class, 'resign'])->name('employee.resign');
    Route::post('manage/{id}', [ResignAndLeftEmployeeController::class, 'manageEmployee'])->name('employee.manage');
    Route::get('resign-employees/', [ResignAndLeftEmployeeController::class, 'resignIndex'])->name('resign-employees.index');
    Route::get('left-employees/', [ResignAndLeftEmployeeController::class, 'leftIndex'])->name('left-employees.index');
    Route::get('left-letter/type-wise/{type}/{id}', [ResignAndLeftEmployeeController::class, 'printLeftLetter'])->name('employee.left-letter');
    Route::get('left-letter/first/{id}', [ResignAndLeftEmployeeController::class, 'firstLetter'])->name('employee.left-letter.first');
    Route::get('left-letter/second/{id}', [ResignAndLeftEmployeeController::class, 'secondLetter'])->name('employee.left-letter.second');
    Route::get('left-letter/third/{id}', [ResignAndLeftEmployeeController::class, 'thirdLetter'])->name('employee.left-letter.third');
    Route::get('active/{id}', [ResignAndLeftEmployeeController::class, 'employeeActive'])->name('employee.active');
    Route::get('restore/{id}', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::delete('permanent-delete/{id}', [EmployeeController::class, 'permanentDelete'])->name('employees.permanent-delete');
    Route::post('bulk-actions', [EmployeeController::class, 'bulkAction'])->name('employees.bulk-action');
    Route::get('shift/changes', [ShiftController::class, 'shiftChange'])->name('shifts.changes');
    Route::get('shift/change/{id}/{employee_id}', [ShiftController::class, 'shiftChangeById']);
    Route::post('promotion/department', [PromotionController::class, 'getHrmDepartment'])->name('promotion.department');
    Route::post('promotion/section', [PromotionController::class, 'getSection'])->name('promotion.section');
    Route::post('promotion/bulk-actions', [PromotionController::class, 'bulkAction'])->name('promotion.bulk-action');
    Route::get('promotion/restore/{id}', [PromotionController::class, 'restore'])->name('promotion.restore');
    Route::delete('promotion/permanent/delete/{id}', [PromotionController::class, 'permanentDelete'])->name('promotion.permanent.delete');
    Route::resource('promotions', PromotionController::class);
    Route::resource('employee-import', ImportEmployeeController::class);
    //master list by designation
    Route::get('master/list', [EmployeeController::class, 'masterList'])->name('employee.master.list');

    // bulk appointment letter index     //person wise appointment letter ajax request     //selected letter print pdf
    Route::get('bulk/appointment/letter', [AppointmentLetterController::class, 'bulkAppointment'])->name('employee.appointment.letter');
    Route::get('create/person/wise/row/{user_id}', [AppointmentLetterController::class, 'createPersonWiseRow'])->name('createPersonWiseRow');
    Route::get('create/selected/letter/print', [AppointmentLetterController::class, 'bulkLetterPrint'])->name('employee.selected.letter.print');

    //test appointment letter & print pdf
    Route::get('appointment-letter-2', [AppointmentLetterController::class, 'appointmentLetter2'])->name('appointmentLetter-2');
    Route::post('/print/appointmentLetter-2', [AppointmentLetterController::class, 'printAppointmentLetter2'])->name('print.appointment.Letter-2');

    Route::get('/print/appointmentLetter', [AppointmentLetterController::class, 'printAppointmentLetter'])->name('printAppointmentLetter');

    // Route::get('test/letter/print', [AppointmentLetterController::class, 'testAppointmentLetterPrint'])->name('testAppointmentLetterPrint');

    //bulk id card routes     //employee bulk id card ajax routes
    Route::get('/idcard', [IdCardController::class, 'generateIdCard'])->name('employee.card');
    Route::get('/print/card', [IdCardController::class, 'printIdCard'])->name('print.employee.card');

    Route::get('arrivals', [ArrivalController::class, 'index'])->name('arrivals.index');
});

Route::prefix('attendances')->group(function () {
    // person wise attendance routes
    Route::resource('persons', PersonWiseAttendanceController::class);
    Route::get('person/wise/attendance', [PersonWiseAttendanceController::class, 'attendanceCreate'])->name('person_wise_attendance');
    // date range absent checker attendance routes
    Route::get('date-range/absence-checker', [DateRangeAbsenceCheckerController::class, 'index'])->name('date_range.absence_checker.index');
    Route::get('date-range/absent-employees', [DateRangeAbsenceCheckerController::class, 'absentEmployees'])->name('date_range.absent_employees');

    // Section wise attendance routes
    Route::resource('section-wise', SectionWiseAttendanceController::class);
    Route::get('create/section-wise-row', [SectionWiseAttendanceController::class, 'createSectionWiseRow'])->name('section-wise.create_row');
    // Route::post('create/division/wise/store', [SectionWiseAttendanceController::class, 'createSectionWiseStore'])->name('attendance.create.section.wise.store');

    //job card routes
    Route::get('/job-card', [JobCardController::class, 'jobCard'])->name('attendance.job.card');
    Route::get('job-card-print', [JobCardController::class, 'jobCardPrint'])->name('attendance.job.card.print');
    Route::get('job-card-summery-print', [JobCardSummaryController::class, 'jobCardSummaryPrint'])->name('job.card.summery.print');

    // Attendance Rapid Update
    Route::get('rapid-update', [AttendanceRapidUpdateController::class, 'attendanceRapidUpdate'])->name('attendance_rapid_update');

    Route::get('employee-wise-rapid-update', [AttendanceRapidUpdateController::class, 'employeeWiseRapidUpdate'])->name('attendance.employeeWiseRapidUpdate');
    Route::get('employee-wise-shift-change/{id}/{shift}', [AttendanceRapidUpdateController::class, 'ShiftAdjustment'])->name('attendance.employeeShiftChange');
    Route::get('rapid/shift-change/adjustment', [AttendanceRapidUpdateController::class, 'AttendanceAdjustment'])->name('attendance_time_adjustment');
    Route::get('missing-attendance/shift-change/{id}/{at_date}/{shift}', [AttendanceRapidUpdateController::class, 'MissingShiftAdjustment'])->name('missing_attendance_shift_change');
    Route::get('clock-in-empty/{id}', [AttendanceRapidUpdateController::class, 'clockInEmpty'])->name('clock_in_empty');
    Route::get('clock-in-change/{id}/{clock_in}', [AttendanceRapidUpdateController::class, 'clockInAdjustment'])->name('clock_in_adjustment');
    Route::get('clock-out-change/{id}/{clock_out}', [AttendanceRapidUpdateController::class, 'clockOutAdjustment'])->name('clock_out_adjustment');
    Route::get('clock-out-empty/{id}', [AttendanceRapidUpdateController::class, 'clockOutEmpty'])->name('clock_out_empty');
    Route::get('clock-out-ts-change/{id}/{clock_out_ts}', [AttendanceRapidUpdateController::class, 'clockOutTsAdjustment'])->name('clock_out_ts_adjustment');
    Route::get('delete-adjustment/{id}', [AttendanceRapidUpdateController::class, 'AdjustmentAttDelete'])->name('adjustment_att_delete');
    Route::get('date-wise-rapid-update', [AttendanceRapidUpdateController::class, 'dateWiseRapidUpdate'])->name('attendance.dateWiseRapidUpdate');

    Route::get('department/wise/attendance/{hrm_department_id}', [PersonWiseAttendanceController::class, 'departmentAttendance']);
    Route::get('shift/wise/attendance/{shift_id}', [PersonWiseAttendanceController::class, 'shiftAttendance']);
    Route::get('person/restore/{id}', [PersonWiseAttendanceController::class, 'restore'])->name('person.restore');
    Route::delete('person/permanent/delete/{id}', [PersonWiseAttendanceController::class, 'permanentDelete'])->name('person.permanent.delete');
    Route::post('person/bulk-actions', [PersonWiseAttendanceController::class, 'bulkAction'])->name('person.bulk-action');

    //missing attendance routes
    Route::get('create/missing', [MissingAttendanceController::class, 'createMissingAttendance'])->name('create.missing.attendance');
    // Route::get('missing/attendance/row/{employeeId}', [AttendanceController::class, 'missingAttendanceRow']);

    // missing wise row
    Route::post('create/person/wise/store', [MissingAttendanceController::class, 'createPersonWiseMissingAttendance'])->name('create.person.wise.store');
    Route::get('division/wise/employee/{divisionId}', [MissingAttendanceController::class, 'divisionWiseEmployee'])->name('division.wise.employee');
    Route::post('missing/store', [MissingAttendanceController::class, 'missingAttendanceStore'])->name('missing.store');

    Route::get('bulk-imports', [BulkAttendanceImportController::class, 'index'])->name('bulk_attendance_imports.index');
    Route::post('bulk-imports/from-text-file', [BulkAttendanceImportController::class, 'importFromTextFile'])->name('bulk_attendance_imports.from_text_file');
    Route::get('log', [AttendanceLogController::class, 'index'])->name('attendance_log.index');

    //Daily Attendance List
    Route::get('daily-attendance', [DailyAttendanceListController::class, 'index'])->name('daily_attendance_list.index');
    Route::get('daily-attendance/excel', [DailyAttendanceListController::class, 'exportExcelFile'])->name('daily_attendance_list.excel');

    //check absent Attendance route
    Route::get('absent/report', [AbsentAttendanceCheckController::class, 'index'])->name('attendance.absent');
    // Route::get('absent/report/print', [AttendanceController::class, 'absentReportPrint'])->name('attendance.absent.print');
    // Route::get('absent/report/excel', [AttendanceController::class, 'absentReportExcel'])->name('attendance.absent.excel');
    Route::get('daily/print', [DailyAttendanceListController::class, 'DailyReportPrint'])->name('daily_attendance.print');
});

Route::prefix('salary')->group(function () {
    Route::resource('salary-settlements', SalarySettlementController::class);
    Route::get('increment/employee/{id}', [SalarySettlementController::class, 'singleSalarySettlement'])->name('single.salary.settlement');
    Route::get('delete/last/settlement/{id}', [SalarySettlementController::class, 'deleteLastSettlement'])->name('delete.last.settlement');
    Route::get('salary/statement/{id}', [SalarySettlementController::class, 'salaryStatement'])->name('salary.statement');
    Route::post('bulk-actions', [SalarySettlementController::class, 'bulkAction'])->name('settlements.bulk-action');
    Route::post('department-wise/store', [SalarySettlementController::class, 'departmentWiseStore'])->name('settlements.department_wise.store');
});
Route::prefix('adjustment')->group(function () {
    // salary adjustment routes
    Route::resource('salaryAdjustments', SalaryAdjustmentsController::class);
    Route::get('salaryAdjustments/restore/{id}', [SalaryAdjustmentsController::class, 'restore'])->name('salaryAdjustments.restore');
    Route::post('salaryAdjustments/bulk-actions', [SalaryAdjustmentsController::class, 'bulkAction'])->name('salaryAdjustments.bulk-action');
    Route::delete('salaryAdjustments/permanent/delete/{id}', [SalaryAdjustmentsController::class, 'permanentDelete'])->name('salaryAdjustments.permanent-delete');
    // overtime adjustment routes
    Route::resource('overtimeAdjustments', OvertimeAdjustmentController::class);
    Route::post('overtimeAdjustments/bulk-actions', [OvertimeAdjustmentController::class, 'bulkAction'])->name('overtimeAdjustments.bulk-action');
    Route::get('overtimeAdjustments/restore/{id}', [OvertimeAdjustmentController::class, 'restore'])->name('overtimeAdjustments.restore');
    Route::delete('overtimeAdjustments/permanent/delete/{id}', [OvertimeAdjustmentController::class, 'permanentDelete'])->name('overtimeAdjustments.permanent-delete');
    // Employee tax adjustment routes
    Route::resource('employee-tax-adjustments', EmployeeTaxAdjustmentController::class);
    Route::post('employee-tax-adjustments/bulk-actions', [EmployeeTaxAdjustmentController::class, 'bulkAction'])->name('employee_tax_adjustments.bulk-action');
    Route::get('employee-tax-adjustments/restore/{id}', [EmployeeTaxAdjustmentController::class, 'restore'])->name('employee-tax-adjustments.restore');
    Route::delete('employee-tax-adjustments/permanent/delete/{id}', [EmployeeTaxAdjustmentController::class, 'permanentDelete'])->name('employee-tax-adjustments.permanent-delete');

    //  advance routes
    Route::resource('salary-advances', SalaryAdvanceController::class);
    // Route::get('salary-advances/{id}', [SalaryAdvanceController::class, 'noticePrint'])->name('notice.print');
    // Route::get('salary-advances/status/{id}', [SalaryAdvanceController::class, 'noticeStatus'])->name('notice.status');
    Route::post('salary-advances/bulk-actions', [SalaryAdvanceController::class, 'bulkAction'])->name('salary-advances.bulk-action');
    Route::get('salary-advances/restore/{id}', [SalaryAdvanceController::class, 'restore'])->name('salary-advances.restore');
    Route::delete('salary-advances/permanent/delete/{id}', [SalaryAdvanceController::class, 'permanentDelete'])->name('salary-advances.permanent-delete');
});

Route::prefix('others')->group(function () {
    // award adjustment routes
    Route::resource('awards', AwardController::class);
    Route::get('awards/restore/{id}', [AwardController::class, 'restore'])->name('awards.restore');
    Route::post('awards/bulk-actions', [AwardController::class, 'bulkAction'])->name('awards.bulk-action');
    Route::delete('awards/permanent/delete/{id}', [AwardController::class, 'permanentDelete'])->name('awards.permanent-delete');
    //  notices routes
    Route::resource('notices', NoticeController::class);
    Route::get('notice/{id}', [NoticeController::class, 'noticePrint'])->name('notice.print');
    Route::get('notice/status/{id}', [NoticeController::class, 'noticeStatus'])->name('notice.status');
    Route::post('notices/bulk-actions', [NoticeController::class, 'bulkAction'])->name('notices.bulk-action');
    Route::get('notices/restore/{id}', [NoticeController::class, 'restore'])->name('notices.restore');
    Route::delete('notices/permanent/delete/{id}', [NoticeController::class, 'permanentDelete'])->name('notices.permanent-delete');
    // Create for Visit-Travel
    Route::resource('visit', VisitController::class);
    Route::delete('visit-file-delete/{id}', [VisitController::class, 'visitFileDelete'])->name('visit_file_delete');
    Route::post('visit/bulk-actions', [VisitController::class, 'bulkAction'])->name('visit.bulk-action');
    // Route::post('visit/show', [VisitController::class, 'bulkAction'])->name('visit.view');
    Route::get('visit/restore/{id}', [VisitController::class, 'restore'])->name('visit.restore');
    Route::delete('visit/permanent/delete/{id}', [VisitController::class, 'permanentDelete'])->name('visit.permanent-delete');
});

// All Reports
Route::get('reports-leave-application', [LeaveApplicationReportController::class, 'index'])->name('leave_report');
Route::get('reports-salary-adjustment', [SalaryAdjustmentReportController::class, 'index'])->name('salary_adjustment_report');
Route::get('reports-salary-increment', [LeaveApplicationReportController::class, 'salaryIncrement'])->name('salary_increment');

// payroll routes
Route::group(['prefix' => 'payroll'], function () {
    Route::prefix('calculation')->group(function () {
        Route::get('/index', [CalculationCheckerController::class, 'index'])->name('calculation.index');
    });
    Route::get('salary-list', [SalaryListController::class, 'salaryList'])->name('payrolls.sallary.list');
    Route::get('salary/list/print', [SalaryListController::class, 'salaryListPrint'])->name('payrolls.salary.list.print');
    Route::get('salary/list/excel-export', [SalaryListController::class, 'salaryListExcelExport'])->name('payrolls.salary.list.excel_export');
    Route::get('salary/payslip/print', [SalaryListController::class, 'printPayslip'])->name('payrolls.payslip.print');
    // Route::get('payroll/slip/report', [PayrollController::class, 'PayrollSlipReport'])->name('payroll.slip.report');
});

// Final settlement
Route::group(['prefix' => 'employees/final-settlement', 'as' => 'final_settlement.'], function () {
    Route::get('index', [FinalSettlementController::class, 'index'])->name('index');
    Route::post('settlement-paper', [FinalSettlementController::class, 'getPaper'])->name('paper');
});

// dashboard settings
Route::get('/hrm/dashboard', [HRMDashboardController::class, 'hrmDashboard'])->name('hrm-dashboard');

// Calculations checker module routes
Route::prefix('calculation')->as('calculation.')->group(function () {
    Route::get('/check-jobCard-Salary', [CalculationCheckerController::class, 'checkJobCardAndSalary'])->name('jobCard_and_salary');
    Route::get('/check-summary-salary', [CalculationCheckerController::class, 'checkSummaryAndSalary'])->name('summary_and_salary');
    Route::get('/check-all-calculation', [CalculationCheckerController::class, 'checkAllCalculation'])->name('all');
});

// Recruitment start here..
Route::group(['prefix' => 'recruitment'], function () {
    Route::get('/applicant-list', [RecruitmentController::class, 'jobApplicantList'])->name('job_applicant_list');
    Route::get('/applicant-view/{id}', [RecruitmentController::class, 'jobApplicantView'])->name('job_applicant_view');
    Route::get('/applicant-download/{id}', [RecruitmentController::class, 'jobApplicantDownload'])->name('job_applicant_download');
    Route::post('/applicant-delete/{id}', [RecruitmentController::class, 'jobApplicantDestroy'])->name('job_applicant_delete');
    Route::post('/applicant-single-select/{id}', [RecruitmentController::class, 'applicantSingle'])->name('applicant_single_select');
    Route::post('applicant-select-bulk-actions', [RecruitmentController::class, 'applicantSelectBulkAction'])->name('applicant_select_bulk-action');
    Route::post('/applicant-selected-interview/{id}', [RecruitmentController::class, 'selectedInterview'])->name('job_applicant_selected_interview');
    Route::get('/applicant-selected-for-interview', [RecruitmentController::class, 'selectedForInterview'])->name('selected_for_interview_list');
    Route::get('/applicant-selected-for-interviewer-view/{id}', [RecruitmentController::class, 'ApplicantSelectedInterviewerView'])->name('selected_for_interviewer_view');
    Route::post('/applicant-selected-interview-single-mail/{id}', [RecruitmentController::class, 'ApplicantSendSingleMailForInterview'])->name('applicant_send_single_mail_for_Interview');
    Route::post('bulk-actions', [RecruitmentController::class, 'sendMailForInterviewBulkAction'])->name('applicant_send_mail_for_Interview_bulk-action');
    Route::get('/applicant-already-mail-for-interview', [RecruitmentController::class, 'alreadyMailForInterview'])->name('already_mail_for_interview_list');
    Route::get('/applicant-already-mail-for-interview/{id}', [RecruitmentController::class, 'alreadyMailForInterviewerView'])->name('already_mail_for_interview_view');
    Route::post('participate-bulk-actions', [RecruitmentController::class, 'participateInterviewBulkAction'])->name('applicant_move_to_participate_interview_bulk-action');
    Route::post('/applicant-single-participate/{id}', [RecruitmentController::class, 'singleParticipateInterviewer'])->name('applicant_move_to_participate_interview_single');
    Route::get('/applicant-interview-participate', [RecruitmentController::class, 'interviewParticipate'])->name('interview_participate_list');
    Route::get('/applicant-interview-participate/{id}', [RecruitmentController::class, 'interviewParticipateView'])->name('interview_participate_list_view');
    Route::post('/applicant-single-final-select/{id}', [RecruitmentController::class, 'applicantFinalSingleSelected'])->name('applicant_final_selected_single');
    Route::post('applicant-final-select-bulk-actions', [RecruitmentController::class, 'applicantFinalSelectBulkAction'])->name('applicant_final_selected_bulk-action');
    Route::get('/applicant-final-selected', [RecruitmentController::class, 'applicantFinalSelected'])->name('applicant_final_selected_list');
    Route::get('/applicant-final-selected/{id}', [RecruitmentController::class, 'applicantFinalSelectedView'])->name('applicant_final_selected_view');
    Route::post('/applicant-single-offer-letter/{id}', [RecruitmentController::class, 'applicantSingleOfferLetterSend'])->name('applicant_single_offer_letter');
    Route::post('applicant-offer-letter-bulk-actions', [RecruitmentController::class, 'applicantOfferLetterBulkAction'])->name('applicant_offer_letter_bulk-action');

    Route::get('/applicant-offer-letter', [RecruitmentController::class, 'applicantOfferLetter'])->name('applicant_offer_letter_list');
    Route::get('/applicant-offer-letter-view/{id}', [RecruitmentController::class, 'applicantOfferLetterView'])->name('applicant_offer_letter_view');
    Route::post('/applicant-single-hired/{id}', [RecruitmentController::class, 'applicantSingleHired'])->name('applicant_single_hired');
    Route::post('/applicant-bulk-hired', [RecruitmentController::class, 'applicantBulkHired'])->name('applicant_bulk_hired');

    Route::get('/applicant-hired-list', [RecruitmentController::class, 'applicantHiredList'])->name('applicant_hired_list');
    Route::get('/applicant-hired-view/{id}', [RecruitmentController::class, 'applicantHiredView'])->name('applicant_hired_view');
    Route::post('/applicant-bulk-reject', [RecruitmentController::class, 'applicantBulkReject'])->name('applicant_bulk_reject');
    Route::get('/applicant-convert/{id}', [ConvertToEmployeeController::class, 'applicantConvert'])->name('applicant_convert');
    Route::get('/convert-employee-list', [RecruitmentController::class, 'convertEmployeeList'])->name('convert_employee_list');
    Route::get('/convert-employee-view/{id}', [RecruitmentController::class, 'convertEmployeeView'])->name('convert_employee_view');

    Route::get('/applicant-reject-list', [RecruitmentController::class, 'applicantRejectList'])->name('applicant_reject_list');
    Route::get('/applicant-reject-view/{id}', [RecruitmentController::class, 'applicantRejectView'])->name('applicant_reject_view');

    Route::get('/interview-list', [InterviewController::class, 'interviewList'])->name('interview_list');
    Route::post('/interview-store', [InterviewController::class, 'interviewStore'])->name('interview_store');
    Route::get('/interview-edit/{id}', [InterviewController::class, 'interviewEdit'])->name('interview_edit');
    Route::post('/interview-update/{id}', [InterviewController::class, 'interviewUpdate'])->name('interview_update');
    Route::delete('/interview-delete/{id}', [InterviewController::class, 'interviewDestroy'])->name('interview_destroy');
    Route::post('/interview-bulk-action', [InterviewController::class, 'interviewBulkAction'])->name('interview_bulk_action');
    Route::post('/interview-permanent-delete/{id}', [InterviewController::class, 'interviewPermanentDelete'])->name('interview_permanent_delete');

    Route::get('/interview-schedule-list', [InterviewScheduleController::class, 'scheduleList'])->name('schedule_list');
    Route::post('/interview-schedule-store', [InterviewScheduleController::class, 'scheduleStore'])->name('schedule_store');
    Route::get('/interview-schedule-edit/{id}', [InterviewScheduleController::class, 'scheduleEdit'])->name('schedule_edit');
    Route::post('/interview-schedule-update/{id}', [InterviewScheduleController::class, 'scheduleUpdate'])->name('schedule_update');
    Route::delete('/interview-schedule-delete/{id}', [InterviewScheduleController::class, 'scheduleDestroy'])->name('schedule_destroy');
    Route::post('/interview-schedule-bulk-action', [InterviewScheduleController::class, 'scheduleBulkAction'])->name('schedule_bulk_action');
    Route::post('/interview-schedule-permanent-delete/{id}', [InterviewScheduleController::class, 'schedulePermanentDelete'])->name('schedule_permanent_delete');

    Route::get('/interview-question-list', [InterviewQuestionController::class, 'interviewQuestionList'])->name('interview_question_list');
    Route::post('/interview-question-store', [InterviewQuestionController::class, 'interviewQuestionStore'])->name('interview_question_store');
    Route::get('/interview-question-edit/{id}', [InterviewQuestionController::class, 'interviewQuestionEdit'])->name('interview_question_edit');
    Route::post('/interview-question-update/{id}', [InterviewQuestionController::class, 'interviewQuestionUpdate'])->name('interview_question_update');
    Route::delete('/interview-question-delete/{id}', [InterviewQuestionController::class, 'interviewQuestionDestroy'])->name('interview_question_destroy');
    Route::post('/interview-question-bulk-action', [InterviewQuestionController::class, 'interviewQuestionBulkAction'])->name('interview_question_bulk_action');
    Route::post('/interview-question-permanent-delete/{id}', [InterviewQuestionController::class, 'interviewQuestionPermanentDelete'])->name('interview_question_permanent_delete');
});

Route::get('/upgrade-plan', [SettingController::class, 'upgradePlan'])->name('upgrade.plan');
