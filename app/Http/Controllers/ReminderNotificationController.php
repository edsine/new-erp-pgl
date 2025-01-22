<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reminder;
use App\Models\ReminderUser;
use App\Jobs\SendReminderNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderNotificationMail;

class ReminderNotificationController extends Controller
{
    /**
     * Send email notification to the logged-in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReminderNotifications()
    {
        $user = auth()->user();  // Get the logged-in user

        // Get the reminders for the logged-in user from the reminderusers table
        $reminders = ReminderUser::whereIn('reminder_id', [2, 3, 4])  // Only send reminders with IDs 2, 3, 4
                                  ->get();

        foreach ($reminders as $reminderUser) {
            $reminder = Reminder::find($reminderUser->reminder_id);
            if ($reminder) {
                try {
                    // Send the email
                    Mail::to($user->email)->send(new ReminderNotificationMail($user, $reminder));
        
                   
                } catch (\Throwable $th) {
                   
                }
                // Dispatch the job to send email notification
               // dispatch(new SendReminderNotification($user, $reminder));
            }
        }

        return response()->json(['message' => 'Reminder notifications have been queued for sending.']);
    }
}
