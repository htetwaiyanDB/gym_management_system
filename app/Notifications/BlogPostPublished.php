<?php

namespace App\Notifications;

use App\Models\BlogPost;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlogPostPublished extends Notification
{
    use Queueable;

    public function __construct(private readonly BlogPost $post)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'type' => 'blog_post',
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'summary' => $this->post->summary,
            'slug' => $this->post->slug,
            'published_at' => $this->post->published_at?->toIso8601String(),
        ];
    }
}
