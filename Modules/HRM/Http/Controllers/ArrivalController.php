<?php

namespace Modules\HRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRM\Interface\ArrivalServiceInterface;
use Modules\HRM\Interface\CommonServiceInterface;
use Modules\HRM\Interface\EmployeeServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ArrivalController extends Controller
{
    private $arrivalService;

    private $commonService;

    private $employeeService;

    public function __construct(EmployeeServiceInterface $employeeService, ArrivalServiceInterface $arrivalService, CommonServiceInterface $commonService)
    {
        $this->arrivalService = $arrivalService;
        $this->commonService = $commonService;
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        // $employees = $this->arrivalService->activeEmployeeFilter($request);
        // $employees =$this->employeeService->activeEmployeeListForID($request);
        $employees = $this->employeeService->getItemByFilter($request);
        $rowCount = $this->employeeService->getRowCount();
        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="employee_id[]" value="'.$row->id.'" class="mt-2 check1">
                    </div>';

                    return $html;
                })
                ->editColumn('section_id', function ($row) {
                    return $row->section->name ?? 'Section is not Specified';
                })
                ->editColumn('designation_id', function ($row) {
                    return $row->designation->name ?? 'Designation is not Specified';
                })
                ->editColumn('address', function ($row) {
                    $address = $row->present_address;
                    $verified_length = strlen($address);
                    $address_result = ($verified_length > 30) ? substr($address, 0, 30).'...' : $address;

                    return '<span title="'.$address.'">'.$address_result.'</span>' ?? 'Address is not Specified';
                })
                ->editColumn('grade_id', function ($row) {
                    return $row->grade->name ?? 'Grade is not Specified';
                })
                ->editColumn('photo', function ($row) {
                    return $this->commonService->showAvatarImage('uploads/employees/', $row->photo);
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type1 = '';
                    $type2 = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type1 = 'Restore';
                        $type2 = 'Permanent Delete';
                        $icon1 = '<i class="fa-solid fa-recycle"></i> ';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i> ';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type1 = 'Edit';
                        $type2 = 'Delete';
                        $icon1 = '<i class="fa-regular fa-edit"></i> ';
                        $icon2 = '<i class="fa-regular fa-trash-alt"></i> ';
                    }
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('hrm_employees_update')) {

                        if (! $row->trashed()) {
                            $html .= '<a class="dropdown-item" style="border-bottom: 1px solid #665f5f;" href="'.route('hrm.employee.view', $row->id).'" id="view"><i class="fa-regular fa-eye"></i> View</a>';
                            $html .= '<a class="dropdown-item '.$action1.'" href="'.route('hrm.employees.'.$action1, $row->id).'" id="'.$action1.'">'.$icon1.$type1.'</a>';
                            $html .= '<a class="dropdown-item" target="_blank" href="'.route('hrm.employee.id.card', $row->id).'" id="id_card"><i class="fa-regular fa-id-card"></i> ID CARD</a>';
                            $html .= '<a class="dropdown-item resign" href="'.route('hrm.employee.resign', $row->id).'" id="resign"><i class="fa-regular fa-pen-nib"></i> Resign</a>';
                            $html .= '<a class="dropdown-item left" href="'.route('hrm.employee.left', $row->id).'" id="left"><i class="fa-regular fa-arrow-right-from-bracket"></i> Left</a>';
                        }
                    }

                    if (auth()->user()->can('hrm_employees_delete')) {
                        if ($row->trashed()) {
                            $html .= '<a class="dropdown-item '.$action1.' " href="'.route('hrm.employees.'.$action1, $row->id).'" id="'.$action1.'">'.$icon1.$type1.'</a>';
                        }
                        $html .= '<a class="dropdown-item '.$action2.' delete" href="'.route('hrm.employees.'.$action2, $row->id).'" id="'.$action2.'">'.$icon2.$type2.'</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check', 'photo', 'address'])
                ->smart(true)
                ->make(true);
        }

        return view('hrm::arrivals.index');
    }
}
