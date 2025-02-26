import DOMPurify from 'dompurify';

window.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("evolutio-subservice-modal");
    const modalTitle = modal.querySelector(".evolutio-subservice-modal__title");
    const modalDescription = modal.querySelector(".evolutio-subservice-modal__description");
    const closeModal = modal.querySelector(".evolutio-subservice-modal__close");

    /**
     * Open the subservice modal
     * @param {string} id The subservice post ID
     * @param {string} name The subservice title
     * @param {string} description The subservice description (html)
     */
    function showModal(id, name, description) {
        modalTitle.textContent = name;
        modalDescription.innerHTML = DOMPurify.sanitize(description);
        modal.style.display = "flex";
        history.replaceState(null, "", "#" + id); // Update URL
    }

    function hideModal() {
        modal.style.display = "none";
        history.replaceState(null, "", window.location.pathname); // Remove #id from URL
    }

    // Handle "Read more" clicks
    document.querySelectorAll(".evolutio-subservice-card__readmore").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const id = this.getAttribute("data-id");
            const name = this.getAttribute("data-name");
            const description = this.getAttribute("data-description");
            showModal(id, name, description);
        });
    });

    closeModal.addEventListener("click", hideModal);

    // Close modal on click outside
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            hideModal();
        }
    });

    // Close modal on Esc key press
    window.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            hideModal();
        }
    });

    // Open modal on page load if there's a valid #id
    function checkURLForModal() {
        const hash = window.location.hash.substring(1);
        if (!hash) return;
        const link = document.querySelector(`.evolutio-subservice-card__readmore[data-id="${hash}"]`);
        if (link) {
            showModal(hash, link.getAttribute("data-name"), link.getAttribute("data-description"));
        }
    }

    checkURLForModal(); // Run on page load
});