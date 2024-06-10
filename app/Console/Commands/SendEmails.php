<?php

namespace App\Console\Commands;

use App\Jobs\SendPostNotificationEmailJob;
use App\Mail\PostNotificationMail;
use App\Models\Post;
use App\Models\PostNotification;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to subscribers for new posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newPosts = Post::whereDoesntHave('notifications')->get();

        foreach ($newPosts as $post) {
            $subscribers = Subscriber::whereHas('subscriptions', function ($query) use ($post) {
                $query->where('website_id', $post->website_id);
            })->get();

            $subscribersToNotify = $subscribers->filter(function ($subscriber) use ($post) {
                return !PostNotification::where('subscriber_id', $subscriber->id)
                    ->where('post_id', $post->id)
                    ->exists();
            });

            foreach ($subscribersToNotify as $subscriber) {
                dispatch(new SendPostNotificationEmailJob($subscriber, $post));
            }
        }
    }
}
