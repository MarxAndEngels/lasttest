<?php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackShippedEmail extends Mailable
{
  use Queueable, SerializesModels;

  public Feedback $feedback;
  public string $viewBlade;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(Feedback $feedback, string $viewBlade, ?string $subject)
  {
    $this->feedback = $feedback;
    $this->viewBlade = $viewBlade;
    $this->subject = $subject;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build(): self
  {
    return $this->markdown($this->viewBlade);
  }
}
