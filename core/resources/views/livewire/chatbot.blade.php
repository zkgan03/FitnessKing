{{-- 

  Author : GAN ZHI KEN

--}}
<div
    class="fixed bottom-10 right-10 z-50 text-sm"
    x-data="{ open: false }"
    @click.outside="open=false"
>
    {{-- Main Chatbot container --}}
    <div
        class="min-w-80 absolute bottom-0 right-4 flex h-[60vh] w-[20vw] translate-y-[80vh] transform flex-col rounded-lg shadow transition-transform duration-300"
        x-bind:class="open ? '!translate-y-[0vh]' : 'translate-y-[80vh]'"
    >

        {{-- Chatbot Header --}}
        <div class="flex h-10 rounded-t-lg bg-gradient-to-b from-primary-500 to-primary-600 p-2 text-white">
            <p class="text-lg font-semibold">Chatbot</p>

            <button
                wire:click="resetChat"
                class="ml-2 rounded-full bg-black px-2 py-1 text-xs text-white hover:bg-gray-600"
            >
                Clear
            </button>

            {{-- Close chatbot button --}}
            <button
                @click="open = false"
                class="absolute right-2 top-2 h-6 w-6 rounded-full"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-6 w-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    >
                    </path>
            </button>
        </div>

        {{-- Chats and Chat history --}}
        <div
            id="conversationBox"
            class="relative flex h-full w-full flex-col overflow-x-auto scroll-smooth bg-darkSurface-500 text-sm"
        >
            @foreach ($chatMessages as $chatMessage)
                <x-chat-bubble :isUser="$chatMessage['role'] == 'User'">
                    {!! $chatMessage['content'] !!}
                </x-chat-bubble>
            @endforeach

            {{-- display as stream When chatbot is responding --}}
            <x-chat-bubble
                id="chat-response-stream"
                wire:stream="responseStream"
                :isUser="false"
                class="!p-0"
            ></x-chat-bubble>

            {{-- typing indicator --}}
            @if ($pending)
                <div
                    id="chat-typing-indicator"
                    class="typing-indicator mb-3 ml-2 flex w-fit self-start rounded-lg bg-gray-200 p-3"
                >
                    <span class="mr-1 h-2 w-2 rounded-full bg-gray-500 opacity-40"></span>
                    <span class="mr-1 h-2 w-2 rounded-full bg-gray-500 opacity-40"></span>
                    <span class="h-2 w-2 rounded-full bg-gray-500 opacity-40"></span>
                </div>
            @endif

            {{-- Scroll to bottom button --}}
            <button
                id="chat-scroll-down"
                class="fixed bottom-20 right-6 hidden w-fit rounded-full bg-primary-500 p-1 text-white hover:opacity-80"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-4"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"
                    >
                    </path>
                </svg>
            </button>
        </div>

        {{-- Chat input & send button --}}
        <form
            wire:submit='sendMessageStream'
            class="flex border-t border-gray-200 bg-darkSurface-500 p-3"
        >
            <input
                id="chat-input-field"
                wire:model="inputMsg"
                type="text"
                autocomplete="off"
                class="mr-2 flex-grow rounded-full border-none bg-darkSurface-400 p-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                placeholder="Type your message here..."
                @if ($pending) disabled @endif
            >
            <button
                id="chat-submit-btn"
                type="submit"
                disabled
                class="cursor-pointer rounded-full border-none bg-primary-500 px-4 py-2 text-white transition-colors duration-300 hover:bg-primary-600 disabled:cursor-not-allowed disabled:bg-primary-500 disabled:opacity-50"
            >
                Send
            </button>
        </form>
    </div>

    {{-- Open Chatbot button --}}
    <div
        @click="open = !open; "
        x-bind:class="open ? 'translate-y-[70vh]' : 'translate-y-0'"
        class="flex h-12 w-12 cursor-pointer items-center justify-center rounded-full bg-primary-600 shadow-lg transition-transform duration-300 hover:scale-110"
    >
        <svg
            class="h-6 w-6 fill-white"
            viewBox="0 0 24 24"
        >
            <path d=" M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
        </svg>
    </div>
</div>

@script
    <script>
        // add mutation observer to scroll to bottom when new message is added and initialize
        document.addEventListener('livewire:initialized', function() {

            console.log('livewire:initialized');
            const conversationBox = document.getElementById('conversationBox');
            conversationBox.scrollTop = conversationBox.scrollHeight;

            const mutationObserver = new MutationObserver((mutations) => {
                // scroll to bottom when new message is added
                if (mutations.some(mutation => mutation.type === 'childList')) {
                    conversationBox.scrollTop = conversationBox.scrollHeight;
                }
            });

            mutationObserver.observe(conversationBox, {
                childList: true
            });

            // Get the chat response element
            const chatResponse = document.querySelector('#chat-response-stream');

            // if the chat is not empty, scroll to the bottom when the text is appending, observe the text content
            const resizeObserver = new ResizeObserver((entires) => {
                entires.forEach((entry) => {
                    if (entry.contentRect.height > 0) {
                        chatResponse.classList.remove('!p-0');
                        conversationBox.scrollTop = conversationBox.scrollHeight;
                    } else {
                        chatResponse.classList.add('!p-0');
                    }
                });
            });

            resizeObserver.observe(chatResponse);

            // Throttle the scroll event listener
            const throttledScrollHandler = throttle(() => {
                console.log(conversationBox.scrollTop, conversationBox.scrollHeight);

                if (conversationBox.scrollTop + conversationBox.clientHeight >= conversationBox
                    .scrollHeight - 100) {
                    console.log('observe');
                    resizeObserver.observe(chatResponse);
                } else {
                    console.log('unobserve');
                    resizeObserver.unobserve(chatResponse);
                }
            }, 200); // Adjust the throttle limit as needed

            conversationBox.addEventListener('scroll', throttledScrollHandler);

            function throttle(func, limit) {
                let lastFunc;
                let lastRan;
                return function() {
                    const context = this;
                    const args = arguments;
                    if (!lastRan) {
                        func.apply(context, args);
                        lastRan = Date.now();
                    } else {
                        clearTimeout(lastFunc);
                        lastFunc = setTimeout(function() {
                            if ((Date.now() - lastRan) >= limit) {
                                func.apply(context, args);
                                lastRan = Date.now();
                            }
                        }, limit - (Date.now() - lastRan));
                    }
                };
            }

            // disable chat-submit-btn when inputMsg is empty
            const chatSubmitBtn = document.getElementById('chat-submit-btn');
            const inputMsg = document.querySelector('#chat-input-field');
            inputMsg.addEventListener('input', function() {
                if (inputMsg.value.trim() === '') {
                    chatSubmitBtn.disabled = true;
                } else {
                    chatSubmitBtn.disabled = false;
                }
            });


            // scroll down button when the chat is at bottom
            const chatScrollDown = document.getElementById('chat-scroll-down');
            chatScrollDown.addEventListener('click', function() {
                conversationBox.scrollTop = conversationBox.scrollHeight;
            });

            // dont show the scroll down button when the chat is at bottom
            conversationBox.addEventListener('scroll', function() {
                if (conversationBox.scrollTop + conversationBox.clientHeight >= conversationBox
                    .scrollHeight) {
                    chatScrollDown.style.display = 'none';
                } else {
                    chatScrollDown.style.display = 'block';
                }
            });
        });
    </script>
@endscript
