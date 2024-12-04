const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentStep = 0;

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
}

function sendMessage(message = null) {
    const input = document.getElementById('chatbox-input');
    const userMessage = message || input.value.trim();
    
    if (!userMessage) return;
    
    if (!message) {
        addMessageToChatbox('You', userMessage);
        input.value = '';
    }

    const typingIndicator = addTypingIndicator();

    fetch(CHAT_HANDLE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
            answer: userMessage,
            step: currentStep
        })
    })
    .then(response => response.json())
    .then(data => {
        if (typingIndicator) typingIndicator.remove();

        if (data.error) {
            addMessageToChatbox('Bot', data.message || 'Sorry, something went wrong. Please try again.');
            return;
        }

        if (data.needsConfirmation) {
            addMessageToChatbox('Bot', `Here's a summary of your application:\n${data.summary}\n\nWould you like to submit this application? (yes/no)`);
            currentStep = 'confirm';
        } else if (data.status === 'complete') {
            addMessageToChatbox('Bot', data.message);
            currentStep = 0;
        } else {
            addMessageToChatbox('Bot', data.question);
            if (data.options) {
                addOptionsToChatbox(data.options);
            }
            currentStep++;
        }
    })
    .catch(error => {
        if (typingIndicator) typingIndicator.remove();
        console.error('Error:', error);
        addMessageToChatbox('Bot', 'Sorry, something went wrong. Please try again.');
    });
}

function addMessageToChatbox(sender, message) {
    const chatbox = document.getElementById('chatbox-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${sender.toLowerCase()}-message`;
    messageDiv.innerHTML = `<strong>${sender}:</strong> ${message}`;
    chatbox.appendChild(messageDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
}

function addOptionsToChatbox(options) {
    const chatbox = document.getElementById('chatbox-messages');
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'chat-options';

    options.forEach(option => {
        const optionButton = document.createElement('button');
        optionButton.className = 'option-button';
        optionButton.textContent = option;
        optionButton.onclick = () => {
            addMessageToChatbox('You', option);
            sendMessage(option);
            optionsDiv.remove();
        };
        optionsDiv.appendChild(optionButton);
    });

    chatbox.appendChild(optionsDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
}

function addTypingIndicator() {
    const chatbox = document.getElementById('chatbox-messages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator';
    typingDiv.innerHTML = 'Bot is typing...';
    chatbox.appendChild(typingDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
    return typingDiv;
}
