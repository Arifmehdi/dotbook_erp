<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\UserActivityLogUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserActivityLogReportController extends Controller
{
    public $userActivityLogUtil;

    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $actions = $this->userActivityLogUtil->actions();
            $subject_types = $this->userActivityLogUtil->subjectTypes();

            $generalSettings = DB::table('general_settings')->first();
            $logs = '';

            $query = DB::table('user_activity_logs')
                ->leftJoin('users', 'user_activity_logs.user_id', 'users.id');

            $query->select(
                'user_activity_logs.id',
                'user_activity_logs.date',
                'user_activity_logs.report_date',
                'user_activity_logs.action',
                'user_activity_logs.subject_type',
                'user_activity_logs.descriptions',
                'users.prefix as u_prefix',
                'users.name as u_name',
                'users.last_name as u_last_name',
            );

            $logs = $this->filteredQuery($request, $query)
                ->orderBy('user_activity_logs.report_date', 'desc');

            return DataTables::of($logs)
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', json_decode($generalSettings->business, true)['date_format']);

                    return date($__date_format.' h:i:s a', strtotime($row->report_date));
                })

                ->editColumn('action_by', fn ($row) => $row->u_prefix.' '.$row->u_name.' '.$row->u_last_name)

                ->editColumn('action', function ($row) use ($actions) {

                    if ($actions[$row->action] == 'Deleted') {

                        return '<strong class="text-danger">'.$actions[$row->action].'</strong>';
                    } elseif ($actions[$row->action] == 'Added') {

                        return '<strong class="text-success">'.$actions[$row->action].'</strong>';
                    } elseif ($actions[$row->action] == 'Updated') {

                        return '<strong class="text_color_updated">'.$actions[$row->action].'</strong>';
                    } elseif ($actions[$row->action] == 'User Login') {

                        return '<strong class="text-success">'.$actions[$row->action].'</strong>';
                    } elseif ($actions[$row->action] == 'User Logout') {

                        return '<strong class="text-danger">'.$actions[$row->action].'</strong>';
                    }

                    return $actions[$row->action];
                })

                ->editColumn('subject_type', function ($row) use ($subject_types) {

                    return $subject_types[$row->subject_type];
                })

                ->editColumn('descriptions', function ($row) {

                    return $row->descriptions;
                })

                ->rawColumns(['date', 'action_by', 'action', 'subject_type', 'descriptions'])
                ->make(true);
        }

        return view('reports.user_activity_log.index');
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('user_activity_logs.user_id', $request->user_id);
        }

        if ($request->action) {

            $query->where('user_activity_logs.action', $request->action);
        }

        if ($request->subject_type) {

            $query->where('user_activity_logs.subject_type', $request->subject_type);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('user_activity_logs.report_date', $date_range); // Final
        }

        return $query;
    }
}
