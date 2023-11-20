<?php

namespace Modules\HRM\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Transformers\EmployeeResource;

class ArrivalController extends Controller
{
    private $employeeService;

    public function __construct(ArrivalServiceInterface $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->activeEmployeeFilter($request);

        return EmployeeResource::collection($employees);
    }
}
