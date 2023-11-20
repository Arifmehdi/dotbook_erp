<?php

namespace Modules\HRM\Interface;

interface AttendanceServiceInterface extends BaseServiceInterface
{
    public function attendanceEmployee($request);

    public function getAttendanceLogPaginated($request);

    public function sectionWiseAttendanceStore($request);

    public function absentAttendanceReport($request);

    public function DateRangeAbsenceCheckerData($request);

    public function getDistinctServiceYears(int $id): iterable;
}
