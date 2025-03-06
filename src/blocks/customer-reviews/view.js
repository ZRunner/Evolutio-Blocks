import Swiper from 'swiper';
import { Pagination, Mousewheel, Navigation } from 'swiper/modules';

window.addEventListener('DOMContentLoaded', () => {
    const reviewsCount = document.getElementsByClassName("evolutio-customer-review__content").length;
    new Swiper('.swiper', {
        modules: [Pagination, Mousewheel, Navigation],
        spaceBetween: 32,
        slidesPerView: "auto",
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: reviewsCount > 3
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        mousewheel: { forceToAxis: true }
    });
});