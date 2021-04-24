<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $app_officer;
    public $employee;
    public $leave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($app_officer, $employee, $leave)
    {
        //
        $this->app_officer =   $app_officer;
        $this->employee =   $employee;
        $this->leave =   $leave;
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

        $btn_url    =   'http://tms.ideaserv.com.ph:8080/pending_lv/lv_details/'.$this->leave['id'].'/'.$this->leave['empno'];
        // $btn_url    =   'http://192.168.0.64:8000/pending_lv/lv_details/'.$this->leave['id'].'/'.$this->leave['empno'];

        return $this->markdown('mails.LeaveMail')->subject('Leave Application')
                                                ->from($from, $name)
                                                ->to($to_email, $to_name)
                                                ->with('name', $name)
                                                ->with('leave', $this->leave)
                                                ->with('url', $btn_url);
    }
}
