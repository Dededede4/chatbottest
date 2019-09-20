<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

use App\Providers\ProviderInterface;

class OnboardingConversation extends Conversation
{
    protected $firstname;

    protected $email;

    protected $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you '.$this->firstname);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->firstname);
        });
    }

    public function run()
    {
        $sentences = $this->provider->getSentences();
        foreach ($sentences as $sentence)
        {
            $this->say($sentence);
        }
        //$this->askFirstname();
    }
}

