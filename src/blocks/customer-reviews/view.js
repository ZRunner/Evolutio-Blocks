window.addEventListener('DOMContentLoaded', () => {
    const reviewsCount = document.getElementsByClassName("evolutio-customer-review__content").length;
    new Swiper('.swiper', {
        spaceBetween: 32,
        slidesPerView: "auto",
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: reviewsCount > 3
        },
        mousewheel: { forceToAxis: true }
    });
});