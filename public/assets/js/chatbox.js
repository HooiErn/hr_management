const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentStep = 0;

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
}

function sendMessage() {
    const input = document.getElementById('chatbox-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessageToChatbox('You', message);
    input.value = '';

    // Show typing indicator
    const typingIndicator = addTypingIndicator();

    // Send to server
    fetch(CHAT_HANDLE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
            answer: message,
            step: currentStep
        })
    })
    .then(response => response.json())
    .then(data => {
        // Remove typing indicator
        if (typingIndicator) typingIndicator.remove();

        if (data.error) {
            addMessageToChatbox('Bot', 'Sorry, something went wrong. Please try again.');
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
    optionsDiv.innerHTML = 'Options: ' + options.join(', ');
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
