document.addEventListener("DOMContentLoaded", function () {
  // Banner slider
  let slideIndex = 0;
  const slides = document.querySelectorAll("#slider .slide");

  function showSlide(index) {
    if (slides.length === 0) return;
    const slider = document.getElementById("slider");
    const slideWidth = slides[0].clientWidth;
    slider.style.transform = `translateX(-${index * slideWidth}px)`;
  }

  function moveSlide(n) {
    slideIndex = (slideIndex + n + slides.length) % slides.length;
    showSlide(slideIndex);
  }

  setInterval(() => {
    moveSlide(1);
  }, 7000);

  window.moveSlide = moveSlide;

  const productSlider = document.getElementById("productSlider");
const products = document.querySelectorAll("#productSlider .product");
let currentProductIndex = 0;

function moveProductSlide(n) {
  const maxIndex = products.length - 1;
  currentProductIndex += n;

  if (currentProductIndex < 0) currentProductIndex = maxIndex;
  if (currentProductIndex > maxIndex) currentProductIndex = 0;

  const targetProduct = products[currentProductIndex];
  if (!targetProduct) return;

  productSlider.scrollTo({
    left: targetProduct.offsetLeft,
    behavior: "smooth",
  });
}

setInterval(() => {
  moveProductSlide(1);
}, 5000);


  window.moveProductSlide = moveProductSlide;

  // Auto-hide slider buttons
  function setupAutoHideSliderButtons(containerSelector) {
    const container = document.querySelector(containerSelector);
    const buttons = container.querySelectorAll('.slider-btn');
    let hideTimer = null;

    function showButtons() {
      buttons.forEach(btn => btn.classList.add('show'));

      if (hideTimer) clearTimeout(hideTimer);
      hideTimer = setTimeout(() => {
        buttons.forEach(btn => btn.classList.remove('show'));
      }, 1500);
    }

    container.addEventListener('mouseenter', showButtons);
    container.addEventListener('mousemove', showButtons);
  }

  setupAutoHideSliderButtons('.banner-slider-container');
  setupAutoHideSliderButtons('.product-slider-container');
});
