<?php

namespace App\Services\Chatbot;

use Carbon\Carbon;
use Psr\Http\Message\StreamInterface;
use App\Services\Chatbot\Enums\ChatRole;
use App\Services\Enrollment\EnrollmentService;
use App\Services\ClassPackage\ClassPackageService;

/**
 * Author:  GAN ZHI KEN
 * 
 **/

class ChatbotServiceUtils
{
    /**
     * Get system prompt message
     * 
     * @return array
     */
    public static function getSystemPrompt(): array
    {
        //content : "Transcript of a dialog, where the User interacts with an Assistant. Assistant is helpful, kind, honest, good at writing, and never fails to answer the User's requests immediately and with precision."

        return [
            'role' => ChatRole::System->value,
            'content' => "Transcript of a dialog, where the User interacts with an Assistant. "
                . "Assistant are a professional gym trainer with expertise in fitness, strength training, nutrition, and injury prevention. "
                . "Assistant's goal is to provide personalized workout routines, nutrition plans, and guidance on proper exercise techniques. "
                . "Assistant are encouraging, motivational, and adapt Assistant's advice to suit individuals at different fitness levels, "
                . "from beginners to advanced athletes. "
                . "Assistant prioritize safety, progressive training, and sustainable habits for long-term health and fitness. "
                . "Ensure Assistant's advice is evidence-based, and when asked, "
                . "explain the reasoning behind Assistant's recommendations in an accessible and supportive manner."
        ];
    }

    /**
     * Read a chunk from a stream, divided by '\x1E' (0x1E) character
     * 
     * @param StreamInterface $stream
     * @param int|null $maxLength
     * @return string
     */
    public static function readChunk(StreamInterface $stream, ?int $maxLength = null): string
    {
        $buffer = '';
        $size = 0;

        while (!$stream->eof()) {
            if ('' === ($byte = $stream->read(1))) {
                return $buffer;
            }
            $buffer .= $byte;
            // Break when a new line is found or the max length - 1 is reached
            if ($byte === "\x1E" || ++$size === $maxLength - 1) {
                break;
            }
        }

        return $buffer;
    }
}