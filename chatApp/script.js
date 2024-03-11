// Function to check the length of the message and display a warning if necessary
function checkMessageLength() {
  const inputField = document.getElementById('message-input');
  const warningMessage = document.getElementById('warningMessage');

  // Check the length of the message in the input field
  if (inputField.value.length > 350) {
    // If the message is longer than 200 characters, display the warning
    warningMessage.style.display = 'block';
  } else {
    // Otherwise, hide the warning
    warningMessage.style.display = 'none';
  }
}

// Add an event listener to the input field to check the message length every time the user types
document.getElementById('message-input').addEventListener('input', checkMessageLength);


// Define the fetchData function outside of the event listener
const fetchData = (callback) => {
    fetch('getMessages.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const sortedMessages = data.sort((a, b) => a.messageId - b.messageId);

            // Select the container where messages will be added
            const container = document.getElementById('messageBoard');

            container.innerHTML = '';
            // Create and append HTML elements for each message
            sortedMessages.forEach(message => {
                const messageElement = document.createElement('div');
                messageElement.className = 'message'; // Optional: for styling
                messageElement.innerHTML = `
                    <div class="message">${message.Message}</div>
                `;
                container.appendChild(messageElement);
            });
            callback(messageBoard);
        });
};

const scrollDown = (element) => {
    element.scrollTop = element.scrollHeight;
};

// Call the fetchData function once the DOM content is loaded
document.addEventListener('DOMContentLoaded', (event) => {
    fetchData(scrollDown);

    scrollDown(messageBoard);

    // Set up an interval to fetch data every 5 seconds
    setInterval(fetchData, 4000);
});