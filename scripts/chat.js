const urlParams = new URLSearchParams(window.location.search);
const chatId = urlParams.get('chat_id');
const userId = urlParams.get('user_id');

if (!chatId || !userId) {
  alert('Missing chat_id or user_id in URL.');
  throw new Error('Missing chat_id or user_id');
}

document.getElementById('chatHeader').textContent = `Chat Room #${chatId}`;

const chatMessagesEl = document.getElementById('chatMessages');
const messageInput = document.getElementById('messageInput');
const attachmentInput = document.getElementById('attachment');
const chatForm = document.getElementById('chatForm');

const MAX_FILE_SIZE_MB = 5;

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

function loadMessages() {
  fetch(`php/chat.php?action=getMessages&chat_id=${encodeURIComponent(chatId)}`)
    .then(response => response.json())
    .then(data => {
      chatMessagesEl.innerHTML = '';
      if (!data.success) {
        alert('Failed to load messages');
        return;
      }
      data.messages.forEach(msg => {
        const msgDiv = document.createElement('div');
        msgDiv.classList.add('message');
        msgDiv.classList.add(msg.sender_id == userId ? 'self' : 'other');

        if (msg.message) {
          const msgText = document.createElement('div');
          msgText.classList.add('text');
          msgText.innerHTML = escapeHtml(msg.message);
          msgDiv.appendChild(msgText);
        }

        if (msg.attachment_url) {
          const fileLink = document.createElement('a');
          fileLink.href = `uploads/${msg.attachment_url}`;
          fileLink.target = "_blank";
          fileLink.rel = "noopener noreferrer";
          fileLink.textContent = msg.attachment_type === 'pdf' ? '📄 View PDF' : '🖼 View Image';
          msgDiv.appendChild(fileLink);
        }

        chatMessagesEl.appendChild(msgDiv);
      });
      chatMessagesEl.scrollTop = chatMessagesEl.scrollHeight;
    })
    .catch(err => {
      console.error('Error loading messages:', err);
    });
}

chatForm.addEventListener('submit', (e) => {
  e.preventDefault();

  const message = messageInput.value.trim();
  const attachment = attachmentInput.files[0];

  // Validate: require either message or attachment
  if (!message && !attachment) {
    alert('Please enter a message or select a file to upload.');
    return;
  }

  // Validate file size if attachment exists
  if (attachment && attachment.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
    alert(`File is too large. Maximum allowed size is ${MAX_FILE_SIZE_MB} MB.`);
    attachmentInput.value = ''; // reset file input
    return;
  }

  const formData = new FormData();
  formData.append('chat_id', chatId);
  formData.append('user_id', userId);
  formData.append('message', message);

  if (attachment) {
    formData.append('attachment', attachment);
  }

  fetch('php/chat.php?action=sendMessage', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      messageInput.value = '';
      attachmentInput.value = '';
      loadMessages();
    } else {
      alert('Failed to send message: ' + data.message);
    }
  })
  .catch(err => {
    console.error('Error sending message:', err);
  });
});

loadMessages();
setInterval(loadMessages, 3000);
