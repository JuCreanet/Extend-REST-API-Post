/*******************
 * Accordion Scripts
 *******************/

jQuery(document).ready(function() {
  jQuery(".set > a").on("click", function(evt) {
    evt.preventDefault();

    //jQuery('html,body').animate({scrollTop: jQuery(this).offset().top}, 200);

    if (jQuery(this).hasClass("active")) {
      jQuery(this).removeClass("active");

      jQuery(this)
        .siblings(".content")

        .slideUp(200);

      jQuery(".set > a i")
        .removeClass("dashicons-arrow-up-alt2")

        .addClass("dashicons-arrow-down-alt2");
    } else {
      jQuery(".set > a i")
        .removeClass("dashicons-arrow-up-alt2")

        .addClass("dashicons-arrow-down-alt2");

      jQuery(this)
        .find("i")

        .removeClass("dashicons-arrow-down-alt2")

        .addClass("dashicons-arrow-up-alt2");

      jQuery(".set > a").removeClass("active");

      jQuery(this).addClass("active");

      jQuery(".content").slideUp(200);

      jQuery(this)
        .siblings(".content")

        .slideDown(200);
    }
  });
});
