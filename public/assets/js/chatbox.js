
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let step = 0;

    // Toggle the visibility of the chatbox
       function toggleChatbox() {
        const chatbox = document.getElementById('chatbox');
        if (chatbox.style.display === 'none' || chatbox.style.display === '') {
            chatbox.style.display = 'block';
            // Show first question when chatbox is opened
            if (step === 0) {
                showNextQuestion("What is your name?");
            }
        } else {
            chatbox.style.display = 'none';
        }
    }

// Function to send messages to the backend and update the chat
function sendMessage() {
    const message = document.getElementById("chatbox-input").value;
    if (message.trim() === '') return;

    // Send message to the backend to process
    fetch(CHAT_HANDLE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify({
            step: step,
            answer: message,
            job_title: '{{ $job_view[0]->job_title }}',
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const chatMessages = document.getElementById("chatbox-messages");

        // Display the user's message
        const messageElem = document.createElement('div');
        messageElem.classList.add('user-message');
        messageElem.textContent = message;
        chatMessages.appendChild(messageElem);

        // If we are in the confirmation step, display the confirmation data
        if (data.step === 'confirm') {
            data.confirmation.forEach(item => {
                const confirmMessage = document.createElement('div');
                confirmMessage.classList.add('system-message');
                confirmMessage.textContent = `${item.question} ${item.answer}`;
                chatMessages.appendChild(confirmMessage);
            });
        } else {
            // Otherwise, show the next question
            const questionElem = document.createElement('div');
            questionElem.classList.add('system-message');
            questionElem.textContent = data.question;
            chatMessages.appendChild(questionElem);
            step = data.step;  // Update the step for next interaction
        }

        // Scroll chat to the bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Clear the input field
        document.getElementById("chatbox-input").value = '';
    });
    }
