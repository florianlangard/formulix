function greet()
{
    //TODO : Call endpoint looking for toCongrat true or false
    //TODO : if true, open modal
    const toCongrat = true;
    if (toCongrat) {
        const bodyContainer = document.querySelector('.body-container');

        let congratsModal = document.createElement('div');
        congratsModal.className = 'congrats-modal';

        let modalContainer = document.createElement('div');
        modalContainer.className = 'congrats-modal-container';
        congratsModal.appendChild(modalContainer);

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
    }  
}

function closeModal()
{
    const modal = document.querySelector('.congrats-modal');
    const button = document.querySelector('.btn.btn-validate');

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.remove();
        }
    }
    button.onclick = function() {
        modal.remove();
    }

}

document.addEventListener("DOMContentLoaded", greet);
document.addEventListener("DOMContentLoaded", closeModal);