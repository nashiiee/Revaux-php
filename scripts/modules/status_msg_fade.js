export function setupStatusMessageFade() {
    const statusMessageWrapper = document.getElementById('statusMessage');
    //console.log('setupStatusMessageFade called.');
    //console.log('statusMessageWrapper:', statusMessageWrapper);
    if (statusMessageWrapper) {
        // Function to fade out the message
        const fadeOutMessage = () => {
            //console.log('Adding fade-out class.');
            statusMessageWrapper.classList.add('fade-out');
            // Remove the element from the DOM after the transition completes
            statusMessageWrapper.addEventListener('transitionend', () => {
                statusMessageWrapper.remove();
            }, { once: true }); // Ensure this listener runs only once
        };

        // 1. Fade out after 5 seconds (adjust time as needed)
        setTimeout(fadeOutMessage, 5000); // 5000 milliseconds = 5 seconds

        // 2. Fade out when clicked
        statusMessageWrapper.addEventListener('click', fadeOutMessage);
    } //else {
        //console.log('statusMessage element not found.');
    //}
}