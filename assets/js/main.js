(function () {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector("body");
    const selectHeader = document.querySelector("#header");
    if (
      !selectHeader.classList.contains("scroll-up-sticky") &&
      !selectHeader.classList.contains("sticky-top") &&
      !selectHeader.classList.contains("fixed-top")
    )
      return;
    window.scrollY > 100
      ? selectBody.classList.add("scrolled")
      : selectBody.classList.remove("scrolled");
  }

  document.addEventListener("scroll", toggleScrolled);
  window.addEventListener("load", toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector(".mobile-nav-toggle");

  function mobileNavToogle() {
    document.querySelector("body").classList.toggle("mobile-nav-active");
    mobileNavToggleBtn.classList.toggle("bi-list");
    mobileNavToggleBtn.classList.toggle("bi-x");
  }
  mobileNavToggleBtn.addEventListener("click", mobileNavToogle);

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll("#navmenu a").forEach((navmenu) => {
    navmenu.addEventListener("click", () => {
      if (document.querySelector(".mobile-nav-active")) {
        mobileNavToogle();
      }
    });
  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll(".navmenu .toggle-dropdown").forEach((navmenu) => {
    navmenu.addEventListener("click", function (e) {
      e.preventDefault();
      this.parentNode.classList.toggle("active");
      this.parentNode.nextElementSibling.classList.toggle("dropdown-active");
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector("#preloader");
  if (preloader) {
    window.addEventListener("load", () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector(".scroll-top");

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100
        ? scrollTop.classList.add("active")
        : scrollTop.classList.remove("active");
    }
  }
  scrollTop.addEventListener("click", (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });

  window.addEventListener("load", toggleScrollTop);
  document.addEventListener("scroll", toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: "ease-in-out",
      once: true,
      mirror: false,
    });
  }
  window.addEventListener("load", aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: ".glightbox",
  });

  /**
   * Init isotope layout and filters
   */
  document.querySelectorAll(".isotope-layout").forEach(function (isotopeItem) {
    let layout = isotopeItem.getAttribute("data-layout") ?? "masonry";
    let filter = isotopeItem.getAttribute("data-default-filter") ?? "*";
    let sort = isotopeItem.getAttribute("data-sort") ?? "original-order";

    let initIsotope;
    imagesLoaded(isotopeItem.querySelector(".isotope-container"), function () {
      initIsotope = new Isotope(
        isotopeItem.querySelector(".isotope-container"),
        {
          itemSelector: ".isotope-item",
          layoutMode: layout,
          filter: filter,
          sortBy: sort,
        }
      );
    });

    isotopeItem
      .querySelectorAll(".isotope-filters li")
      .forEach(function (filters) {
        filters.addEventListener(
          "click",
          function () {
            isotopeItem
              .querySelector(".isotope-filters .filter-active")
              .classList.remove("filter-active");
            this.classList.add("filter-active");
            initIsotope.arrange({
              filter: this.getAttribute("data-filter"),
            });
            if (typeof aosInit === "function") {
              aosInit();
            }
          },
          false
        );
      });
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function (swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener("load", function (e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: "smooth",
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll(".navmenu a");

  function navmenuScrollspy() {
    navmenulinks.forEach((navmenulink) => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (
        position >= section.offsetTop &&
        position <= section.offsetTop + section.offsetHeight
      ) {
        document
          .querySelectorAll(".navmenu a.active")
          .forEach((link) => link.classList.remove("active"));
        navmenulink.classList.add("active");
      } else {
        navmenulink.classList.remove("active");
      }
    });
  }
  window.addEventListener("load", navmenuScrollspy);
  document.addEventListener("scroll", navmenuScrollspy);
})();

// Change the prodcts catalog;

// document.querySelectorAll(".service-link").forEach((link) => {
//   link.addEventListener("click", function (event) {
//     event.preventDefault();

//     // Remove active class from all links
//     document
//       .querySelectorAll(".service-link")
//       .forEach((link) => link.classList.remove("active"));
//     // Add active class to the clicked link
//     this.classList.add("active");

//     // Hide all service content sections
//     document
//       .querySelectorAll(".service-info")
//       .forEach((section) => (section.style.display = "none"));

//     // Get the related content ID from the data attribute
//     const serviceId = this.getAttribute("data-service");
//     // Show the selected service content
//     document.getElementById(serviceId).style.display = "block";
//   });
// });

// document.addEventListener("DOMContentLoaded", function () {
//   const serviceLinks = document.querySelectorAll(".products-link");
//   const serviceInfoSections = document.querySelectorAll(".products-info");

//   serviceLinks.forEach((link) => {
//     link.addEventListener("click", function (e) {
//       e.preventDefault(); // Prevent default anchor click behavior

//       // Remove 'active' class from all links
//       serviceLinks.forEach((link) => link.classList.remove("actives"));

//       // Add 'active' class to the clicked link
//       this.classList.add("actives");

//       // Get the service ID from the data attribute
//       const serviceId = this.getAttribute("data-service");

//       // Hide all service info sections
//       serviceInfoSections.forEach((section) => {
//         section.style.display = "none";
//       });

//       // Show the selected service info section
//       const selectedSection = document.getElementById(serviceId);
//       if (selectedSection) {
//         selectedSection.style.display = "block";
//       }
//     });
//   });
// });

// // Categories based on buttons
// // Get the tab elements
// const overviewTab = document.querySelector(
//   '[data-bs-target="#features-tab-5"]'
// );
// const credentialsTab = document.querySelector(
//   '[data-bs-target="#features-tab-6"]'
// );

// if (overviewTab && credentialsTab) {
//   // Get the product sections
//   const products1Section = document.getElementById("Products-1");
//   const products2Section = document.getElementById("Products-2");

//   // Function to handle tab clicks
//   function handleTabClick(event) {
//     event.preventDefault();

//     // Remove active class from all tabs
//     const allTabs = document.querySelectorAll(".nav-link");
//     allTabs.forEach((tab) => tab.classList.remove("active", "show"));

//     // Add active class to clicked tab
//     const clickedTab = event.currentTarget;
//     clickedTab.classList.add("active", "show");

//     // Show/hide product sections based on which tab was clicked
//     if (clickedTab === overviewTab) {
//       products1Section.style.display = "block";
//       products2Section.style.display = "none";
//     } else if (clickedTab === credentialsTab) {
//       products1Section.style.display = "none";
//       products2Section.style.display = "block";
//     }
//   }

//   // Add click event listeners to tabs
//   overviewTab.addEventListener("click", handleTabClick);
//   credentialsTab.addEventListener("click", handleTabClick);

//   // Initialize the page with Products-1 visible and Products-2 hidden
//   document.addEventListener("DOMContentLoaded", () => {
//     products1Section.style.display = "block";
//     products2Section.style.display = "none";

//     // Ensure the overview tab is active initially
//     overviewTab.classList.add("active", "show");
//     credentialsTab.classList.remove("active", "show");
//   });

//   document.addEventListener("DOMContentLoaded", function () {
//     // Get tab elements
//     const tabLinks = document.querySelectorAll(".features .nav-link");

//     // Get product sections that we want to toggle
//     const products1Section = document.getElementById("Products-1");
//     const products2Section = document.getElementById("Products-2");

//     // Function to handle tab switching
//     function handleTabClick(event) {
//       event.preventDefault();

//       // Remove active class from all tabs
//       tabLinks.forEach((tab) => {
//         tab.classList.remove("active", "show");
//       });

//       // Add active class to clicked tab
//       event.currentTarget.classList.add("active", "show");

//       // Get the target from the clicked tab
//       const targetId = event.currentTarget.getAttribute("data-bs-target");

//       // Show/hide product sections based on which tab was clicked
//       if (targetId === "#features-tab-1") {
//         products1Section.style.display = "block";
//         products2Section.style.display = "none";
//       } else if (targetId === "#features-tab-2") {
//         products1Section.style.display = "none";
//         products2Section.style.display = "block";
//       }
//     }

//     // Add click event listeners to each tab
//     tabLinks.forEach((tab) => {
//       tab.addEventListener("click", handleTabClick);
//     });

//     // Initialize the page state
//     function initializePage() {
//       // Show Products-1 and hide Products-2 initially
//       if (products1Section && products2Section) {
//         products1Section.style.display = "block";
//         products2Section.style.display = "none";
//       }

//       // Make sure the first tab is active
//       const firstTab = document.querySelector(".features .nav-link");
//       if (firstTab) {
//         firstTab.classList.add("active", "show");
//       }
//     }

//     // Call initialize function
//     initializePage();
//   });
// }

// // Mobile Prducct section
// const btnAccordion1 = document.getElementById("btnAccordion1");
// const btnAccordion2 = document.getElementById("btnAccordion2");

// if(btnAccordion1)
// {
//   btnAccordion1.addEventListener("click", function () {
//   document.getElementById("accordion11").style.display = "block";
//   document.getElementById("accordion12").style.display = "none";
//   this.classList.add("active");
//   document.getElementById("btnAccordion2").classList.remove("active");
// });
// }

// if(btnAccordion2)
// {
//   btnAccordion2.addEventListener("click", function () {
//   document.getElementById("accordion12").style.display = "block";
//   document.getElementById("accordion11").style.display = "none";
//   this.classList.add("active");
//   document.getElementById("btnAccordion1").classList.remove("active");
// });
// }
