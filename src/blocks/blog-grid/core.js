//@ts-check

window.addEventListener("DOMContentLoaded", () => {
    /**
     * @typedef {Object} Post
     * @property {string} date - The publication date of the post.
     * @property {Object} title - The title object.
     * @property {string} title.rendered - The rendered title.
     * @property {Object} excerpt - The excerpt object.
     * @property {string} excerpt.rendered - The rendered excerpt.
     * @property {string} link - The post link.
     */

    /**
     * Helper to render a post card in plain HTML.
     * @param {Post} post - The post object from the REST API.
     * @returns {string} The HTML string for the post card.
     */
    function renderPostCard(post) {
        const formattedDate = new Date(post.date).toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        return (
            '<div class="evolutio-bloggrid-card">' +
            '<h2 class="evolutio-bloggrid-title">' + (post.title.rendered || '(No title)') + '</h2>' +
            '<div class="evolutio-bloggrid-date">' + formattedDate + '</div>' +
            '<div class="evolutio-bloggrid-excerpt">' + (post.excerpt.rendered || '') + '</div>' +
            '<div class="evolutio-bloggrid-readmore">' +
            '<a href="' + post.link + '" class="evolutio-bloggrid-readmore-link">Lire la suite</a>' +
            '</div>' +
            '</div>'
        );
    }

    /**
     * Debounce helper to delay API calls while typing.
     * @template T
     * @param {(arg: T) => void} func - The function to debounce.
     * @param {number} wait - The debounce delay in milliseconds.
     * @returns {(arg: T) => void} The debounced function.
     */
    function debounce(func, wait) {
        /** @type {NodeJS.Timeout | undefined} */
        let timeout;
        return function (arg) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(arg), wait);
        };
    }

    /**
     * Infinite scroll and live search logic.
     */
    let loading = false;
    let noMorePosts = false;
    /** @constant {number} */
    const POSTS_PER_PAGE = 10;
    const searchInput = /** @type {HTMLInputElement | null} */ (document.getElementById('evolutio-bloggrid-search-input'));

    /**
     * Fetches posts from the REST API.
     * @param {string} [searchValue=''] - The search query.
     * @param {boolean} [resetGrid=false] - Whether to reset the grid before fetching.
     */
    async function fetchPosts(searchValue = '', resetGrid = false) {
        if (loading) return;
        if (!searchValue && noMorePosts) return;
        loading = true;

        /** @type {HTMLElement} */
        const grid = document.querySelector('.evolutio-bloggrid-grid');

        let page = resetGrid ? 1 : Math.ceil(grid.children.length / POSTS_PER_PAGE) + 1;
        if (resetGrid) {
            noMorePosts = false;
            grid.innerHTML = '';
            page = 1;
        }

        const url = new URL('/wp-json/wp/v2/posts', document.baseURI);
        url.searchParams.append('per_page', POSTS_PER_PAGE.toString());
        url.searchParams.append('page', page.toString());
        url.searchParams.append('orderby', 'date');
        url.searchParams.append('order', 'desc');
        if (searchValue) {
            url.searchParams.append('search', searchValue);
        }

        try {
            const response = await fetch(url.toString());
            if (!response.ok) {
                const errData = await response.json();
                if (response.status === 400 && errData.code === "rest_post_invalid_page_number") {
                    noMorePosts = true;
                    return [];
                }
                throw new Error('Error fetching posts');
            }
            /** @type {Post[]} */
            const data = await response.json();
            if (data.length < POSTS_PER_PAGE) {
                noMorePosts = true;
            }
            data.forEach(function (post) {
                grid?.insertAdjacentHTML('beforeend', renderPostCard(post));
            });
            loading = false;
        } catch (error) {
            console.error(error);
            loading = false;
        }
    }

    // Attach infinite scroll
    window.addEventListener('scroll', function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            fetchPosts();
        }
    });

    // Debounced search handler
    const handleSearch = debounce(function (/** @type {Event} */ event) {
        fetchPosts((/** @type {HTMLInputElement} */ (event.target)).value, true);
    }, 300);

    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }

    // Fetch initial posts
    waitForElement('.evolutio-bloggrid-grid').then(() => {
        fetchPosts('', true);
    });
});

/**
 * Waits for an element to be added to the DOM.
 * 
 * @param {string} selector - The CSS selector to wait for.
 * @returns {Promise<Element>} A promise that resolves with the element.
 */
function waitForElement(selector) {
    const existingElement = document.querySelector(selector);
    if (existingElement) {
        return Promise.resolve(existingElement);
    }
    return new Promise((resolve) => {
        const observer = new MutationObserver((mutations, observer) => {
            const element = document.querySelector(selector);
            if (element) {
                observer.disconnect();
                resolve(element);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    });
}

