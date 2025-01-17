<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\Enums\ChatRole;


/**
 * Author:  GAN ZHI KEN
 * 
 */
class Chatbot extends Component
{

    #[Validate('required|string|max:255')]
    public $inputMsg;
    public $pending = false;
    public array $chatMessages = [];


    public function mount()
    {
        $chatbotService = app(ChatbotService::class);
        $this->chatMessages = $chatbotService->getChatMessages();
    }

    public function render()
    {
        return view('livewire.chatbot');
    }

    public function resetChat()
    {
        $chatbotService = app(ChatbotService::class);
        $chatbotService->clearMessageHistory();
        $this->chatMessages = [];
    }

    public function sendMessageStream()
    {
        $this->validate(); // validate input

        $input = $this->inputMsg; // store input becuz it will be reset later

        // update properties
        $this->reset('inputMsg'); // empty the variable & input field
        $this->pending = true;
        $this->chatMessages[] = [
            'role' => ChatRole::User->value,
            'content' => $input,
        ]; // add user message to chat history (also to show in view)

        $this->dispatch('send-message-stream', $input); // dispatch event because we wanted to update the view immediately
    }

    #[On('send-message-stream')]
    public function streamMsg($msg)
    {
        $chatbotService = app(ChatbotService::class); // get from service container

        $responseStream = $chatbotService->sendStream($msg); // get response stream

        $chatResponse = ''; // store chat response
        foreach ($responseStream as $response) {

            $response_stream = nl2br(e($response)); //newline to br for displaying

            // stream response to instant update view
            $this->stream(
                to: 'responseStream',
                content: $response_stream,
                replace: false,
            );

            $chatResponse .= $response; // append to chat response
        }

        // update properties
        $this->chatMessages[] = [
            'role' => ChatRole::Assistant->value,
            'content' => $chatResponse,
        ]; // add assistant message to chat history

        $this->pending = false;
    }


}
