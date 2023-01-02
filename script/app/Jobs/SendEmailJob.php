<?php

namespace App\Jobs;

use App\Mail\AssignNewProjectMail;
use App\Mail\AssignNewTaskMail;
use App\Mail\CollabMail;
use App\Mail\InviteUserMail;
use App\Mail\OrderMail;
use App\Mail\OrderMailExpired;
use App\Mail\PlanMail;
use App\Mail\SupportMail;
use App\Mail\TaskCompleteMail;
use App\Mail\UserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->details['email'];
        $type = $this->details['type'];

        unset($this->details['type'], $this->details['email']);

        switch ($type) {
            case 'plan':
                $data = new PlanMail($this->details);
                Mail::to($email)->send($data);
                break;
            case 'order_expired':
                $data = new OrderMailExpired($this->details);
                Mail::to($email)->send($data);
                break;
            case 'invite':
                $data = new InviteUserMail($this->details);
                Mail::to($email)->send($data);
                break;
            case 'collab':
                $data = new CollabMail($this->details);
                Mail::to($email)->send($data);
                break;
            case 'taskcomplete':
                $data = new TaskCompleteMail($this->details);
                Mail::to($email)->send($data);
                break;
            case 'assigntask':
                $data = new AssignNewTaskMail($this->details);
                Mail::to($email)->send($data);
                break;
            case 'assignproject':
                $data = new AssignNewProjectMail($this->details);
                Mail::to($email)->send($data);
                break;          
            case 'support':
                $data = new SupportMail($this->details);
                Mail::to($email)->send($data);
                break;     
            case 'usermail':
                $data = new UserMail($this->details);
                Mail::to($email)->send($data);
                break;        
            default:
                $data = new OrderMail($this->details);
                Mail::to($email)->send($data);
                break;
        }

        
    }
}
