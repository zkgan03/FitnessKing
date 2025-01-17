<?php

namespace App\Services\Chatbot\Enums;

/**
 * Author:  GAN ZHI KEN
 * 
 */
enum ChatRole: string
{
    case System = 'System';
    case User = 'User';
    case Assistant = 'Assistant';
}
