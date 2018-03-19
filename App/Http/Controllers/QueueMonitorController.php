<?php

namespace Pyaesone17\QueueMonitor\App\Http\Controllers;

use App\Http\Controllers\Controller;
use QueueMonitor\App\Models\FailedJob;
use Illuminate\Http\Request;
use Pyaesone17\QueueMonitor\App\Http\Middleware\Authenticate;

class QueueMonitorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function index()
    {
        $failed_jobs = FailedJob::get();

        $today_failed_jobs = FailedJob::today('failed_at')->get();
        $except_today_failed_jobs = FailedJob::exceptToday('failed_at')->get();

        $thisMonth_failed_jobs = FailedJob::thisMonth('failed_at')->get();
        $except_thisMonth_failed_jobs = FailedJob::exceptThisMonth('failed_at')->get();

        $today_chart = $this->toTodayChartData($today_failed_jobs, $except_today_failed_jobs);
        $thisMonth_chart = $this->toThisMonthChartData($thisMonth_failed_jobs,$except_thisMonth_failed_jobs);

        $chart = $this->toChartData($failed_jobs);

        return view('queue-monitor::index',
            compact('failed_jobs','today_chart','thisMonth_chart','thisMonth_failed_jobs','today_failed_jobs', 'chart')
        );
    }

    public function manage($type)
    {
        switch ($type) {
            case 'today':
                $failed_jobs = FailedJob::today('failed_at')->get();
                $except_today_failed_jobs = FailedJob::exceptToday('failed_at')->get();
                $chart =  $this->toTodayChartData($today_failed_jobs, $except_today_failed_jobs);
                break;

            case 'this-month':
                $failed_jobs = FailedJob::thisMonth('failed_at')->get();
                $except_thisMonth_failed_jobs = FailedJob::exceptThisMonth('failed_at')->get();
                $chart = $this->toThisMonthChartData($failed_jobs, $except_thisMonth_failed_jobs);
                break;
            
            default:
                $failed_jobs = FailedJob::get();
                $chart = $this->toChartData($failed_jobs);

                break;
        }

        return view('queue-monitor::manage',
            compact('failed_jobs','type', 'chart')
        );
    }

    public function show($id)
    {
        $job = FailedJob::find($id);
        
        return view('queue-monitor::show',compact('job'));
    }


    public function update(Request $request,$id)
    {
        $job = app()->make('queue.failer')->find($id);

        $this->retryJob($job);
        app()->make('queue.failer')->forget($id);

        if($request->filled('_rdt')) {
            return redirect()->to($request->_rdt)->with(['status' => 'success' , 'message' => 'Successfully requeued to jobs store']);
        }

        return redirect()->back()->with(['status' => 'success' , 'message' => 'Successfully requeued to jobs store']);
    }

    public function destroy(Request $request,$id)
    {
        // FailedJob::findOrFail($id)->delete();

        if($request->filled('_rdt')) {
            return redirect()->to($request->_rdt)->with(['status' => 'success' , 'message' => 'Successfully deleted from queue jobs']);
        }
        return redirect()->back()->with(['status' => 'success' , 'message' => 'Successfully deleted from queue jobs']);
    }

    protected function toTodayChartData($today_failed_jobs, $except_today_failed_jobs)
    {
        return json_encode([
            'labels'   => [ 'Today', 'All'],
            'datasets' => [
                [
                    'data'                 => [$today_failed_jobs->count(), $except_today_failed_jobs->count()],
                    'backgroundColor'      => [ 
                        config('queue-monitor.colors.nice_2'),
                        config('queue-monitor.colors.main')
                    ]
                ],
            ],
        ]);
    }
 
    protected function toThisMonthChartData($thisMonth_failed_jobs, $except_thisMonth_failed_jobs)
    {
        return json_encode([
            'labels'   => [ 'ThisMonth', 'All'],
            'datasets' => [
                [
                    'data'                 => [$thisMonth_failed_jobs->count(), $except_thisMonth_failed_jobs->count()],
                    'backgroundColor'      => [ 
                        config('queue-monitor.colors.nice_2'),
                        config('queue-monitor.colors.main')
                    ]
                ],
            ],
        ]);
    }

    protected function toChartData($failed_jobs)
    {
        $grouped = $failed_jobs->groupBy(function ($item, $key) {
            return $item->payload->displayName;
        });

        $grouped = $grouped->each(function ($gp) {
            $gp->items_count = $gp->count();
        });

        return json_encode([
            'labels'   => $grouped->keys()->toArray(),
            'datasets' => [
                [
                    'data'                 => $grouped->pluck('items_count')->toArray(),
                    'backgroundColor'      => array_values(config('queue-monitor.colors'))
                ],
            ],
        ]);
    }

    /**
     * Retry the queue job.
     *
     * @param  \stdClass  $job
     * @return void
     */
    protected function retryJob($job)
    {
        app()->make('queue')->connection($job->connection)->pushRaw(
            $this->resetAttempts($job->payload), $job->queue
        );
    }

    /**
     * Reset the payload attempts.
     *
     * Applicable to Redis jobs which store attempts in their payload.
     *
     * @param  string  $payload
     * @return string
     */
    protected function resetAttempts($payload)
    {
        $payload = json_decode($payload, true);

        if (isset($payload['attempts'])) {
            $payload['attempts'] = 0;
        }

        return json_encode($payload);
    }
}
