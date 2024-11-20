<?php

use App\Models\Task;
use App\Notifications\GenericNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('send:reminder', function () {
    $tasks = Task::with('user')->where('reminder_at', now())->get();
    foreach ($tasks as $task) {
        if ($task->user->last_whatsapp_at->diffInHours(now()) >= 24) {
            $task->user->notify(new \App\Notifications\ReminderNotification());
        } else {
            $message = 'Lembrete de tarefa: ' . $task->description;
            $task->user->nofity(new GenericNotification($message));
        }
    }
});
