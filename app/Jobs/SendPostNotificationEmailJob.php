<?php

namespace App\Jobs;

use App\Mail\PostNotificationMail;
use App\Models\PostNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPostNotificationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscriber;
    protected $post;

    /**
     * Create a new job instance.
     */
    public function __construct($subscriber, $post)
    {
        $this->subscriber = $subscriber;
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->subscriber->email)->send(new PostNotificationMail($this->post));

        PostNotification::create([
            'post_id' => $this->post->id,
            'subscriber_id' => $this->subscriber->id,
            'sent_at' => now(),
        ]);
    }
}
