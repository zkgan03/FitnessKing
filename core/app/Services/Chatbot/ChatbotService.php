<?php

namespace App\Services\Chatbot;

use GuzzleHttp\Client;
use App\Services\Chatbot\Enums\ChatRole;

/**
 * Author:  GAN ZHI KEN
 * 
 */

class ChatbotService
{
    private $chatbotClient;

    public function __construct()
    {
        // Initialize chatbot client
        $this->chatbotClient = new Client([
            'headers' => [
                'x-api-key' => env('CHATBOT_API_KEY'),
                'Content-Type' => 'application/json',
                'Accept' => 'text/event-stream', // accept server-sent events
            ],
            'base_uri' => env('CHATBOT_API_BASE_URL'),
            'stream' => true, // enable streaming
        ]);
    }

    /**
     * Send user message to chatbot and return response as streamed response
     * @param string $msg
     * @return \Generator 
     */
    public function sendStream(string $msg): \Generator
    {

        $messageHistory = $this->getMessageHistory(); //load chat message history

        // encapsulate user message
        $data = [
            'role' => ChatRole::User->value,
            'content' => $msg,
        ];

        $messageHistory[] = $data; // add user message to chat history

        $requestMsg = array_slice($messageHistory, -5); // Select 5 latest messages

        // add system prompt at the beginning if there are more than 5 messages
        // to maintain context
        if (count($messageHistory) >= 5) {
            array_unshift($requestMsg, ChatbotServiceUtils::getSystemPrompt());
        }

        try {

            $response = $this->chatbotClient->request('POST', 'Chat/History/Stream', [
                'json' => ["messages" => $requestMsg],
            ]);

        } catch (\Exception $e) {

            // Handle error in making the API request
            yield "Error during API request: " . $e->getMessage();
            return;

        }

        $body = $response->getBody();

        $completeResponse = '';
        while (!$body->eof()) {
            //read line by line
            $line = ChatbotServiceUtils::readChunk($body);

            $cleanLine = preg_replace('/\x1E$/', '', $line); //remove speical char at the end of the line
            $cleanLine = str_replace("User:", "", $cleanLine); // remove "User:" from the End of line if exists

            $completeResponse .= $cleanLine; // append to the complete response

            yield $cleanLine; // return as stream
        }

        $messageHistory[] = [
            'role' => ChatRole::Assistant->value,
            'content' => $completeResponse,
        ];

        $this->saveChatMessages($messageHistory); // save chat message history
    }

    /**
     * Retrieve All chat messages between user and assistant
     * @return array
     */
    public function getChatMessages(): array
    {
        $chatHistory = $this->getMessageHistory();
        array_shift($chatHistory); //remove the system message
        return $chatHistory;
    }

    public function clearMessageHistory(): void
    {
        session()->forget('chatMessages');
    }

    /**
     * Retrieve chat message history from db, including system prompt
     * @return array
     */
    private function getMessageHistory(): array
    {
        $messageHistory = [];

        // check if chat messages exist in session
        if (!session()->has('chatMessages')) {

            // initialize system prompt
            session()->put('chatMessages', [
                ChatbotServiceUtils::getSystemPrompt(),
            ]);
        }

        $messageHistory = session()->get('chatMessages');

        return $messageHistory;
    }


    /**
     * Save chat messages to session
     * @param array $messageHistory
     * @return void
     */
    private function saveChatMessages(array $messageHistory): void
    {
        session()->put('chatMessages', $messageHistory);
    }
}