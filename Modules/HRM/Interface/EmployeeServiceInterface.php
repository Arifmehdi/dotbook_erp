<?php

namespace Modules\HRM\Interface;

use Modules\HRM\Entities\Employee;

interface EmployeeServiceInterface
{
    public function all();

    public function store(array $attribute);

    public function find($id);

    public function update(array $attribute, $id);

    public function trash($id);

    public function getTrashedItem();

    public function bulkTrash($request);

    public function permanentDelete($id);

    public function bulkPermanentDelete($request);

    public function restore($id);

    public function bulkRestore($request);

    public function getRowCount();

    public function getTrashedCount();

    public function getRowCountForResigned();

    public function getRowCountForLeft();

    public function getLastInsertedEmployee();

    public function getItemByFilter($request);

    public function activeEmployee();

    public function leftEmployee();

    public function resignedEmployee();

    public function makeLeftEmployee($request, $id);

    public function makeResignedEmployee($request, $id);

    public function getEmployee_DailyRemuneration(int $id): float;

    public function activeEmployeesWithOtherInfo(): iterable;

    public function employeeActiveList();

    public function employeeActiveListWithId();

    public function getById(int $id): ?Employee;

    public function employeeList($request);

    // public function activeEmployeeList();
    // public function activeEmployeeListWithId();
    public function activeEmployeeListForID($request);

    public function allEmployeeListWithSelected();
}
