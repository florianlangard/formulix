function greet()
{
    //TODO : Call endpoint looking for toCongrat true or false
    //TODO : if true, open modal
    const confetti = require('canvas-confetti');
    const toCongrat = true;

    if (toCongrat) {
        const bodyContainer = document.querySelector('.body-container');

        let congratsModal = document.createElement('div');
        congratsModal.className = 'congrats-modal';

        let modalContainer = document.createElement('div');
        modalContainer.className = 'congrats-modal-container';
        congratsModal.appendChild(modalContainer);

        let closeButton = document.createElement('img');
        closeButton.setAttribute('src', 'assets/rectangle-xmark-solid.svg');
        closeButton.className = 'modal-close';
        modalContainer.appendChild(closeButton);

        let trophy = document.createElement('img');
        trophy.setAttribute('src', 'assets/trophy-solid.svg');
        trophy.className = 'modal-trophy';
        modalContainer.appendChild(trophy);

        let modalHeader = document.createElement('h3');
        modalHeader.textContent = "GG!";
        modalContainer.appendChild(modalHeader);

        let modalContent = document.createElement('p');
        modalContent.textContent = "texte de congratulation ici";
        modalContainer.appendChild(modalContent);

        let buttonElement = document.createElement('button');
        buttonElement.textContent = "Merci :)";
        buttonElement.className = 'btn btn-validate';
        modalContainer.appendChild(buttonElement);

        bodyContainer.appendChild(congratsModal);

        let myCanvas = document.createElement('canvas');
        modalContainer.appendChild(myCanvas);
        var myConfetti = confetti.create(myCanvas, {
            resize: true,
            useWorker: true
            });
        // myConfetti({
        // particleCount: 100,
        // spread: 160
        // });
        let end = Date.now() + (2 * 1000);
        let colors = ['#15151e', '#E10600'];
        (function frame()
        {
            myConfetti({
                particleCount: 2,
                angle: 60,
                spread: 65,
                origin: { x: 0, y: 0.5 },
                startVelocity: 40,
                colors: colors
            });
            myConfetti({
                particleCount: 2,
                angle: 120,
                spread: 65,
                origin: { x: 1, y: 0.5 },
                startVelocity: 40,
                colors: colors
            });
            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
        setTimeout(() => {
            modalContainer.removeChild(myCanvas);
        }, 4 * 1000);
    }  
}

function closeModal()
{
    const modal = document.querySelector('.congrats-modal');
    const button = document.querySelector('.btn.btn-validate');
    const cross = document.querySelector('.modal-close');

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.remove();
        }
    }
    button.onclick = function() {
        modal.remove();
    }
    cross.onclick = function() {
        modal.remove();
    }

}

document.addEventListener("DOMContentLoaded", greet);
document.addEventListener("DOMContentLoaded", closeModal);