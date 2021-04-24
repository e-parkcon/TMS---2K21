<?php

namespace App\Jobs;

use App\Mail\LeaveMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

use Auth;

class SendLeaveMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employee;
    public $leave;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee, $leave)
    {
        //
        $this->employee =   $employee;
        $this->leave    =   $leave;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $app_officer = Auth::user()->app_officer('L');

        Mail::to($app_officer['email'])->send(new LeaveMailable($app_officer, $this->employee, $this->leave));
    }
}
