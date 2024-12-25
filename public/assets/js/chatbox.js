const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let currentStep = 0;

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
}

async function sendMessage(userMessage, fromQuickOption = false) {
    const input = document.getElementById('chat-input');
    const trimmedMessage = userMessage || input.value.trim();
    
    if (!trimmedMessage) return; // If there is no input, return directly

    // Prevent duplicate messages
    if (!fromQuickOption) {
        addMessageToChatbox('You', trimmedMessage); // Add user message to chatbox
    }
    input.value = ''; // Clear the input box

    const typingIndicator = addTypingIndicator(); // Add typing indicator

    // Predefined responses
    const predefinedResponses = {
        "how to apply": "To apply for a job, please visit our careers page and submit your application online.",
        "interview tips": "Prepare your CV, review job requirements, and highlight relevant experiences.",
        "intro your company": "We are a leading enterprise company specializing in innovative solutions.",
        "contact customer service": "Please contact our customer service via WhatsApp at +601234567.",
        "hello": "Hi, how can I assist you today?",
        "hi": "Hi, how can I assist you today?",
        "thanks": "You're welcome!"
    };

    // Check if user input contains keywords for predefined responses
    const lowerCaseMessage = trimmedMessage.toLowerCase();
    if (fromQuickOption) {
        // Directly respond with the predefined response
        if (lowerCaseMessage.includes("how to apply")) {
            const response = predefinedResponses["how to apply"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("interview tips")) {
            const response = predefinedResponses["interview tips"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("intro your company")) {
            const response = predefinedResponses["intro your company"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("contact customer service") || lowerCaseMessage.includes("live chat")) {
            const response = predefinedResponses["contact customer service"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } 
    } else {
        // Only check for predefined responses if not from quick option
        if (lowerCaseMessage.includes("apply")) {
            const response = predefinedResponses["how to apply"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("tips")) {
            const response = predefinedResponses["interview tips"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("company")||lowerCaseMessage.includes("info") ){
            const response = predefinedResponses["intro your company"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("contact") || lowerCaseMessage.includes("live chat") || lowerCaseMessage.includes("chat")) {
            const response = predefinedResponses["contact customer service"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; // Return directly
        } else if (lowerCaseMessage.includes("thanks") || lowerCaseMessage.includes("thank")) {
            const response = predefinedResponses["thanks"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; 
        }else if (lowerCaseMessage.includes("hello") || lowerCaseMessage.includes("hi")) {
            const response = predefinedResponses["hello"];
            if (typingIndicator) typingIndicator.remove();
            addMessageToChatbox('Bot', response);
            return; 
        }
    }

    // Send request to the server if no predefined response was found
    try {
        const response = await fetch('/chat/message', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                question: trimmedMessage
            })
        });

        const data = await response.json();
        if (typingIndicator) typingIndicator.remove(); // Remove typing indicator

        if (data.error) {
            addMessageToChatbox('Bot', data.message || 'Sorry, something went wrong. Please try again later. If the issue persists, it may be due to maintenance.');
            return;
        }

        addMessageToChatbox('Bot', data.answer || 'How can I assist you?');
    } catch (error) {
        if (typingIndicator) typingIndicator.remove();
        console.error('Error:', error);
        addMessageToChatbox('Bot', 'Sorry, something went wrong. Please try again later. If the issue persists, it may be due to maintenance.');
    }
}

function addMessageToChatbox(sender, message) {
    const chatbox = document.getElementById('content-box');
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message`;

    // Use the bot icon URL defined in the Blade template
    if (sender === 'Bot') {
        messageDiv.innerHTML = `
            <img src="${botIconUrl}" alt="Bot" class="bot-icon">
            <div class="bot-message">${message}</div>
        `;
    } else {
        messageDiv.innerHTML = `<div class="user-message">${message}</div>`;
    }

    chatbox.appendChild(messageDiv);
    
    // Add a line break for spacing after the message
    chatbox.appendChild(document.createElement('br'));

    chatbox.scrollTop = chatbox.scrollHeight; // Scroll to the bottom
}

function addTypingIndicator() {
    const chatbox = document.getElementById('content-box');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'typing-indicator';
    typingDiv.innerHTML = 'Bot is typing...';
    chatbox.appendChild(typingDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
    return typingDiv;
}

// Add clickable options for common questions
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
            // Call sendMessage with the predefined response
            sendMessage(option.value, true); // Indicate that this is from a quick option
        };
        optionsContainer.appendChild(button);
    });

    // Add a line break for spacing
    optionsContainer.appendChild(document.createElement('br'));

    // Clear previous options if they exist
    const existingOptions = document.querySelector('.quick-options');
    if (existingOptions) {
        existingOptions.remove();
    }

    // Append options at the bottom of the chatbox
    document.getElementById('content-box').appendChild(optionsContainer);
}

// Call addQuickOptions when the chatbox is opened
document.addEventListener('DOMContentLoaded', () => {
    addQuickOptions();

    // Send welcome message when the chatbox is opened
    sendWelcomeMessage();
});

function sendWelcomeMessage() {
    const welcomeMessage = "Welcome to our website! We're glad to have you here. If you need any assistance, feel free to reach out.";
    addMessageToChatbox('Bot', welcomeMessage);
    
    // After sending the welcome message, add quick options
    addQuickOptions();
}

document.getElementById('chat-input').addEventListener('keypress', handleKeyPress);

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); 
        sendMessage(); 
    }
}
