$(function () {
  $("#regForm").on("submit", function (e) {
    e.preventDefault();
    const $btn = $("#submitBtn");
    $btn.prop("disabled", true).text("Submitting...");

    // simple client-side validation example
    const name = $("#fullname").val().trim();
    if (name.length < 2) {
      alert("Please enter a valid name");
      $btn.prop("disabled", false).text("Submit");
      return;
    }

    const data = $(this).serialize();

    $.ajax({
      url: "submit.php",
      method: "POST",
      data: data,
      dataType: "html", // server returns formatted HTML card
      success: function (html) {
        $("#resultArea").html(html).removeClass("hidden");
        // add print button handler (button exists inside returned HTML)
        $("#printBtn").on("click", function () {
          window.print();
        });
        $btn.prop("disabled", false).text("Submit");
        // optionally scroll to result
        $("html,body").animate(
          { scrollTop: $("#resultArea").offset().top - 20 },
          400
        );
      },
      error: function (xhr) {
        alert("Submission failed. Try again.");
        $btn.prop("disabled", false).text("Submit");
      },
    });
  });
});
