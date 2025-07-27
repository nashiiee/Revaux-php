export function setupImageSlider() {
  const slides = document.querySelectorAll('.slide');
  let currentIndex = 0;
  const totalSlides = slides.length;

  function showSlide(index) {
    slides.forEach((slide) => {
      slide.classList.remove('active');
    });

    // Add the 'active' class to the current slide
    slides[index].classList.add('active');
  }

  // Show the first slide initially
  showSlide(currentIndex);

  // Function to go to the next slide
  function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
  }

  // Function to go to the previous slide
  function prevSlide() {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
    showSlide(currentIndex);
  }

  // Event listeners for the buttons
  document.getElementById('nextBtn').addEventListener('click', nextSlide);
  document.getElementById('prevBtn').addEventListener('click', prevSlide);

  // Automatic slider transition every 5 seconds (if no hover or button click)
  let sliderInterval = setInterval(nextSlide, 5000);

  // Pause the slider on hover
  const slider = document.querySelector('.hero-slider');
  slider.addEventListener('mouseenter', () => {
    clearInterval(sliderInterval);  // Stop auto sliding
  });

  slider.addEventListener('mouseleave', () => {
    sliderInterval = setInterval(nextSlide, 5000);  // Restart auto sliding
  });
}
