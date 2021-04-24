<?php

namespace App\Jobs;

use App\Mail\OTMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

use Auth;

class SendOTMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employee;
    public $refno;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employee, $refno)
    {
        //
        $this->employee =   $employee;
        $this->refno    =   $refno;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $app_officer = Auth::user()->app_officer('O');

        Mail::to($app_officer['email'])->send(new OTMailable($app_officer, $this->employee, $this->refno));
    }
}
