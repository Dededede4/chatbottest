<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Extensions\ElementButton;

use App\Providers\ProviderInterface;

class OnboardingConversation extends Conversation
{
    protected $firstname;

    protected $email;

    protected $provider;

    const   PAYLOAD_RETURN_LIGUE1 = 'ligue 1';
    const   PAYLOAD_RETURN_CV = 'cv';

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function sayLigue1()
    {
        $sentences = $this->provider->getSentences();
        foreach ($sentences as $sentence)
        {
            $this->say($sentence);
        }
    }

    public function sayCV()
    {
        $this->say('Mon CV est ici : https://lnkd.in/dQwHXm6');
    }

    public function run()
    {
        $this->ask(
            (ButtonTemplate::create('Bonjour ! Voulez-vous voir les derniers résulats de la ligue 1, ou accéder à mon CV ?'))
            ->addButton(ElementButton::create('La ligue 1 ! ⚽ ') ->type('postback')->payload(self::PAYLOAD_RETURN_LIGUE1))
            ->addButton(ElementButton::create('Votre cv Monsieur 🧐')->type('postback')->payload(self::PAYLOAD_RETURN_CV))
            , function(Answer $answer){
                if ($answer->getValue() === self::PAYLOAD_RETURN_LIGUE1)
                {
                    $this->sayLigue1();
                }
                else if ($answer->getValue() === self::PAYLOAD_RETURN_CV)
                {
                    $this->sayCV();
                }
            });
    }
}

