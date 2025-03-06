// @ts-check

/** @typedef {{
 *  id: number,
 *  title: { rendered: string },
 *  status: 'associate' | 'collaborator',
 *  phone: string,
 *  email: string,
 *  linkedin: string,
 *  description: string,
 *  experience: string,
 *  diplomas: string,
 *  expertise: string,
 *  profile_image_src: string,
 * }} Member */

window.addEventListener("DOMContentLoaded", async () => {
    const modal = /** @type {HTMLDialogElement} */ (document.getElementById("evolutio-memberslist-modal"));
    const modalPhoto = /** @type {HTMLImageElement} */ (modal.querySelector(".evolutio-memberslist-modal__photo"));
    const modalTitle = modal.querySelector(".evolutio-memberslist-modal__title");
    const modalStatus = modal.querySelector(".evolutio-memberslist-modal__status");
    const modalPhone = /** @type {HTMLAnchorElement} */ (modal.querySelector(".evolutio-memberslist-modal__phone"));
    const modalEmail = /** @type {HTMLAnchorElement} */ (modal.querySelector(".evolutio-memberslist-modal__email"));
    const modalLinkedin = /** @type {HTMLAnchorElement} */ (modal.querySelector(".evolutio-memberslist-modal__linkedin"));
    const modalDescription = modal.querySelector(".evolutio-memberslist-modal__description");
    const modalExp = modal.querySelector(".evolutio-memberslist-modal__experience");
    const modalDiplomas = modal.querySelector(".evolutio-memberslist-modal__diplomas");
    const modalExpertise = modal.querySelector(".evolutio-memberslist-modal__expertise");
    const closeModal = modal.querySelector(".evolutio-dialog__close");

    const API_URL = "/wp-json/wp/v2/team_member";
    /** @type {Map<string, Member>} */
    const members = new Map();

    async function fetchMembers() {
        try {
            const response = await fetch(API_URL);
            if (!response.ok) throw new Error("Failed to fetch members");
            /** @type {Member[]} */
            const data = await response.json();
            data.forEach(member => members.set(member.id.toString(), member));
        } catch (error) {
            console.error("Error fetching members:", error);
        }
    }

    /**
     * Open the member modal
     * @param {string} id The member post ID
     */
    function showModal(id) {
        const member = members.get(id);
        if (!member) return;

        modalPhoto.src = member.profile_image_src;
        modalPhoto.alt = "Photograph of " + member.title.rendered;
        modalTitle.textContent = member.title.rendered;
        switch (member.status) {
            case "associate":
                modalStatus.textContent = "Associé";
                break;
            default:
                modalStatus.textContent = "Collaborateur";
                break;
        }
        modalPhone.href = "tel:" + member.phone;
        modalPhone.textContent = member.phone;
        modalEmail.href = "mailto:" + member.email;
        modalEmail.textContent = member.email;
        modalLinkedin.href = "https://linkedin.com/in/" + member.linkedin;
        modalDescription.textContent = member.description;
        modalExp.textContent = member.experience;
        modalDiplomas.textContent = member.diplomas;
        modalExpertise.textContent = member.expertise;
        modal.showModal();
        history.replaceState(null, "", "#" + id); // Update URL
        document.body.style.overflow = "hidden"; // Prevent scroll
    }

    function hideModal() {
        modal.close();
        history.replaceState(null, "", window.location.pathname); // Remove #id from URL
        document.body.style.overflow = ""; // Restore scroll
    }

    document.querySelectorAll(".evolutio-memberslist-card").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const id = this.getAttribute("data-id");
            showModal(id);
        });
    });

    closeModal.addEventListener("click", hideModal);

    // Close modal on click outside
    window.addEventListener("click", event => {
        if (event.target === modal) {
            hideModal();
        }
    });

    // Open modal on page load if there's a valid #id
    function checkURLForModal() {
        const hash = window.location.hash.substring(1);
        if (hash && members.has(hash)) showModal(hash);
    }

    await fetchMembers();
    checkURLForModal();
});
