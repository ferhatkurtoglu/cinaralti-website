$(document).ready(function () {
  // Nice Select Initialization
  // $('select').niceSelect();
  AOS.init({
    once: true,
    duration: 1000,
    disable: function () {
      var maxWidth = 991;
      return window.innerWidth < maxWidth;
    },
  });

  inlineSVG.init({
    svgSelector: ".inline-svg", // the class attached to all images that should be inlined
    initClass: "inline-svg-active", // class added to <html>
  });

  //***ISOTOPE***
  // Portfolio-01
  $(window).load(function () {
    $(".navigation-active").isotope({
      itemSelector: ".grid-item",
      layoutMode: "fitRows",
    });
  });

  $(window).load(function () {
    $(".portfolio-v2").isotope({
      itemSelector: ".grid-item",
      percentPosition: true,
      masonry: {
        // use outer width of grid-sizer for columnWidth
        columnWidth: 1,
      },
    });
  });

  // change is-checked class on buttons
  $(".isotope-nav").each(function (i, buttonGroup) {
    var $buttonGroup = $(buttonGroup);
    $buttonGroup.on("click", "a", function () {
      $buttonGroup.find(".active").removeClass("active");
      $(this).addClass("active");
    });
  });

  // filter items on button click
  $(".navigation-list").on("click", "li", function () {
    $(this).addClass("active").siblings().removeClass("active");
    var filterValue = $(this).attr("data-filter");
    $(".isotope-navigation").isotope({
      filter: filterValue,
    });
  });
});

$(window).on("load", function () {
  $("#clock").countdown("2024/10/10", function (event) {
    var $this = $(this).html(
      event.strftime(
        "" +
          '<span class="counter-item">%d <span class="counter-postfixer">Days</span></span> : ' +
          '<span class="counter-item">%H <span class="counter-postfixer">Hours</span></span> : ' +
          '<span class="counter-item">%M <span class="counter-postfixer">Minutes</span></span> : ' +
          '<span class="counter-item">%S <span class="counter-postfixer">Seconds</span></span> '
      )
    );
  });
});

$(window).on("load", function () {
  $("body").addClass("loading");
  setTimeout(function () {
    $(".preloader-wrapper").fadeOut(100);
    $("body").removeClass("loading");
  }, 200);
  setTimeout(function () {
    $(".preloader-wrapper").remove();
  }, 200);
});

//pagination

$(document).ready(function () {

  // change active class on pagination
  $(".pagination-wrapper").each(function (i, buttonGroup) {
    var $buttonGroup = $(buttonGroup);
    $buttonGroup.on("click", ".btn-main", function () {
      $buttonGroup.find(".active").removeClass("active");
      $(this).addClass("active");
    });
  });
});

//donate
$(document).ready(function () {
  console.log("Donate input script yüklendi.");

  $(".input-money").on("input", function () {
    console.log("Input event tetiklendi. Mevcut değer:", $(this).val());
    var inputVal = $(this).val();

    // Sadece rakamları elde et (₺ ve nokta gibi karakterleri kaldır)
    var digits = inputVal.replace(/\D/g, "");

    if (digits === "") {
      $(this).val("");
      return;
    }

    // Her 3 haneden sonra nokta koyarak biçimlendir
    var formatted = digits.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // Başına ₺ ekle ve değeri güncelle
    $(this).val("₺" + formatted);
  });
});
