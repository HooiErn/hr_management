const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function sendMessage(userMessage, fromQuickOption = false) {
    const input = document.getElementById('chat-input');
    const trimmedMessage = userMessage || input.value.trim();
    
    if (!trimmedMessage) return;

    if (!fromQuickOption) {
        addMessageToChatbox('You', trimmedMessage);
    }
    input.value = '';

    const typingIndicator = addTypingIndicator();

    try {
        const response = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                message: trimmedMessage
            })
        });

        const data = await response.json();
        if (typingIndicator) typingIndicator.remove();

        if (data.success) {
            addMessageToChatbox('Bot', data.message, data.isHtml);
            
            // Add suggestion message for first-time users
            if (!fromQuickOption && !sessionStorage.getItem('suggestionsShown')) {
                setTimeout(() => {
                    addMessageToChatbox('Bot', 
                        "You can also try asking me about:\n" +
                        "• Jobs for specific age (e.g., 'jobs for age 25')\n" +
                        "• Experience-based jobs (e.g., '3 years experience')\n" +
                        "• Location-based jobs (e.g., 'jobs in Kuala Lumpur')\n" +
                        "• Job types (e.g., 'full-time positions')"
                    );
                    sessionStorage.setItem('suggestionsShown', 'true');
                }, 1000);
            }
        } else {
            addMessageToChatbox('Bot', 'Sorry, something went wrong. Please try again later.');
        }
    } catch (error) {
        if (typingIndicator) typingIndicator.remove();
        console.error('Error:', error);
        addMessageToChatbox('Bot', 'Sorry, something went wrong. Please try again later.');
    }
}

function addMessageToChatbox(sender, message, isHtml = false) {
    const chatbox = document.getElementById('content-box');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message`;

    if (sender === 'Bot') {
        messageDiv.innerHTML = `
            <img src="${botIconUrl}" alt="Bot" class="bot-icon">
            <div class="bot-message">${isHtml ? message : escapeHtml(message).replace(/\n/g, '<br>')}</div>
        `;
    } else {
        messageDiv.innerHTML = `<div class="user-message">${escapeHtml(message)}</div>`;
    }

    chatbox.appendChild(messageDiv);
    chatbox.appendChild(document.createElement('br'));
    chatbox.scrollTop = chatbox.scrollHeight;
}

function addTypingIndicator() {
    const chatbox = document.getElementById('content-box');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'chat-message typing-indicator';
    typingDiv.innerHTML = `
        <img src="${botIconUrl}" alt="Bot" class="bot-icon">
        <div class="bot-message">Typing...</div>
    `;
    chatbox.appendChild(typingDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
    return typingDiv;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function addQuickOptions() {
    const optionsContainer = document.createElement('div');
    optionsContainer.className = 'quick-options';

    const options = [
        { text: "How to apply?", value: "how to apply" },
        { text: "Interview tips", value: "interview tips" },
        { text: "Company info", value: "intro your company" },
        { text: "Contact customer service", value: "contact customer service" }
    ];

    options.forEach(option => {
        const button = document.createElement('button');
        button.innerText = option.text;
        button.onclick = () => {
            sendMessage(option.value, true);
        };
        optionsContainer.appendChild(button);
    });

    optionsContainer.appendChild(document.createElement('br'));

    const existingOptions = document.querySelector('.quick-options');
    if (existingOptions) {
        existingOptions.remove();
    }

    document.getElementById('content-box').appendChild(optionsContainer);
}

document.addEventListener('DOMContentLoaded', () => {
    addQuickOptions();
    sendWelcomeMessage();
    
    // Add click handler for send button
    document.getElementById('send-button').addEventListener('click', () => {
        sendMessage();
    });

    // Add enter key handler
    document.getElementById('chat-input').addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    });
});

function sendWelcomeMessage() {
    const welcomeMessage = "Welcome! 👋 How can I help you today?\n\n" +
                          "You can ask me about:\n" +
                          "• Job opportunities\n" +
                          "• Age requirements\n" +
                          "• Experience levels\n" +
                          "• Or use the quick options below";
    addMessageToChatbox('Bot', welcomeMessage);
}
