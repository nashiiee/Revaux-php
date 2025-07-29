import { setupAboutToggle } from "./modules/about_toggle.js";
import { setupCategoryDropdown } from "./modules/category_dropdown.js";
import { setupSortDropdown } from "./modules/sort_dropdown.js";
import { setupImageGallery } from "./modules/image_gallery.js";
import { setupImageSlider } from "./modules/image_slider.js";
import { setupZoomPane } from "./modules/zoom_pane.js";
import { setupQuantityControl } from "./modules/quantity_control.js";
import { setupPaymentToggle } from "./modules/payment_toggle.js";
import { setupSizeToggle } from "./modules/size_toggle.js";
import { setupGuestRedirects } from "./modules/guest_redirects.js";
import { setupCartSubmission } from "./modules/cart_submission.js";
import { setupCartPage } from "./modules/cart_page.js";
import { setupStatusMessageFade } from "./modules/status_msg_fade.js";

document.addEventListener('DOMContentLoaded', () => {
  setupCategoryDropdown();// Category dropdown for product filtering
  setupSortDropdown();// Sort dropdown for product sorting 
  setupImageGallery();// Image gallery for product images in product_info
  setupImageSlider();// Image slider for product images in homepage and index
  setupZoomPane();// Zoom pane for product images in product_info
  setupAboutToggle();// About section toggle
  setupQuantityControl();// Quantity control for cart items + product info
  setupPaymentToggle();// Payment method toggle for checkout
  setupSizeToggle();// Size selection for products
  setupGuestRedirects();// Redirects for guest users
  setupCartSubmission();  // Cart submission handling
  setupCartPage();// Cart page features (quantity AJAX, select all, etc)
  setupStatusMessageFade(); // Status message fade effect
});