<?php

namespace App\Mail;

use App\Models\Overtime;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Auth;

class OTMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $app_officer;
    public $employee;
    public $refno;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($app_officer, $employee, $refno)
    {
        //
        $this->app_officer =   $app_officer;
        $this->employee =   $employee;
        $this->refno =   $refno;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $name = $this->employee['fname'] . ' ' . $this->employee['lname'];
        $from = $this->employee['email'];

        $to_email   =   $this->app_officer['email'];
        $to_name    =   $this->app_officer['name'];

        $overtime   =   Overtime::where('refno', $this->refno)->where('overtime.empno', $this->employee['empno'])->get();
        // $url        =   'http://192.168.0.64:8000/pending_ot/ot_lists/'.$this->refno.'/'.$this->employee['empno'];
        $url        =   'http://tms.ideaserv.com.ph:8080/pending_ot/ot_lists/'.$this->refno.'/'.$this->employee['empno'];

        return $this->markdown('mails.OTMail')->subject('Overtime Application')
                                            ->from($from, $name)
                                            ->to($to_email, $to_name)
                                            ->with('name', $name)
                                            ->with('overtime', $overtime)
                                            ->with('url', $url);
    }
}
