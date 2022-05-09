import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

// Create an instance of Notyf
const notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'center',
        y: 'top'
    },
});

let messages = document.querySelectorAll('.notyf-message');

messages.forEach(message => {
    if (message.className === 'notyf-message success') {
        notyf.success(message.innerHTML);
    }

    if (message.className === 'notyf-message error') {
        notyf.error(message.innerHTML);
    }

});