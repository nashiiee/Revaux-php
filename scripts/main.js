import { setupAboutToggle } from "./modules/about_toggle.js";
import { setupCategoryDropdown } from "./modules/category_dropdown.js";
import { setupSortDropdown } from "./modules/sort_dropdown.js";
import { setupImageGallery } from "./modules/image_gallery.js";
import { setupImageSlider } from "./modules/image_slider.js";
import { setupZoomPane } from "./modules/zoom_pane.js";
import { setupQuantityControl } from "./modules/quantity_control.js";
import { setupPaymentToggle } from "./modules/payment_toggle.js";

document.addEventListener('DOMContentLoaded', () => {
  setupCategoryDropdown();   // Category sidebar toggles (Headwear, Tops, Bottoms, Footwear) + Sidecategories (for category pages and search results)
  setupSortDropdown();       // Custom sorting dropdown
  setupImageGallery();       // Main image & thumbnail switching (product_info page)
  setupImageSlider();        // Image Slider for home page with automatic transition and button controls
  setupZoomPane();           // Zoom pane hover effect on product main image (used in product_info page)
  setupAboutToggle();        // See more / See less for product description with fade effect
  setupQuantityControl();    // Quantity +/- with press-and-hold effect (product_info + view_cart page)
  setupPaymentToggle();      // Payment method selection toggle(Cash on Delivery or E-wallet in payment page)
});