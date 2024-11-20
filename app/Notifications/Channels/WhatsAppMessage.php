<?php

namespace App\Notifications\Channels;

class WhatsAppMessage
{
    public $content;
    public $contentSid;
    public $variables;

    public function content($content): self
    {
        $this->content = $content;
        return $this;
    }
    public function contentSid($contentSid): self
    {
        $this->contentSid = $contentSid;
        return $this;
    }
    public function variables($variables): self
    {
        $this->variables = json_encode($variables);
        return $this;
    }
}
