const checkbox = document.getElementById('activate-captcha');
const wrapper = document.getElementById('puzzle-wrapper');
const board = document.getElementById('puzzle-board');
const verifyBtn = document.getElementById('verify-btn');

let firstClicked = null;

checkbox.onclick = () => {
    wrapper.style.display = "block";
    board.innerHTML = "";
const randomId = Math.floor(Math.random() * 10000);
const imageUrl = `https://picsum.photos/300/300?random=${randomId}`;
 const numbers = [0, 1, 2, 3].sort(() => Math.random() - 0.5);

    numbers.forEach((pos) => {
        const piece = document.createElement('div');
        piece.className = 'tile'; 
        piece.setAttribute('data-id', pos);
piece.style.backgroundImage = `url('${imageUrl}')`;
        const x = (pos % 2) * 150;
        const y = Math.floor(pos / 2) * 150;
        piece.style.backgroundPosition = `-${x}px -${y}px`;

        piece.onclick = function() {
            if (!firstClicked) {
                firstClicked = this;
                this.classList.add('selected');
            } else {
              
                const tempBG = this.style.backgroundPosition;
                const tempID = this.getAttribute('data-id');

                this.style.backgroundPosition = firstClicked.style.backgroundPosition;
                this.setAttribute('data-id', firstClicked.getAttribute('data-id'));

                firstClicked.style.backgroundPosition = tempBG;
                firstClicked.setAttribute('data-id', tempID);

                firstClicked.classList.remove('selected');
                firstClicked = null;
            }
        };
        board.appendChild(piece);
    });
};

verifyBtn.onclick = () => {
    const tiles = Array.from(board.children);
    
    const isCorrect = tiles.every((tile, i) => tile.getAttribute('data-id') == i);

    if (isCorrect) {
        checkbox.checked = true;
        checkbox.disabled = true;
        wrapper.style.display = "none";
    
        document.getElementById('captcha-status').value = "solved"; 
        alert("Captcha validé !");
    } else {
        checkbox.checked = false;
        alert("Le puzzle est incorrect, réessayez.");
    }
};
