$(function () {
  function generateMembershipID() {
    const now = new Date();
    const year = now.getFullYear();
    const rand = Math.floor(100 + Math.random() * 900); // 3-digit
    return `LIB-${year}-${rand}`; // e.g. LIB-2025-523
  }

  // set initial membership id
  $("#membership_id").val(generateMembershipID());

  // regenerate ID on reset
  $("#resetBtn").on("click", function () {
    setTimeout(() => {
      $("#membership_id").val(generateMembershipID());
    }, 10);
  });

  $("#memberForm").on("submit", function (e) {
    e.preventDefault();
    const $btn = $("#submitBtn");
    $btn.prop("disabled", true).text("Registering...");

    // simple client-side validation
    const name = $("#fullname").val().trim();
    const email = $("#email").val().trim();
    if (name.length < 2) {
      alert("Please enter a valid name");
      $btn.prop("disabled", false).text("Register");
      return;
    }
    if (email.length < 5 || !email.includes("@")) {
      alert("Please enter a valid email");
      $btn.prop("disabled", false).text("Register");
      return;
    }

    const data = $(this).serialize();

    $.ajax({
      url: "submit.php",
      method: "POST",
      data: data,
      dataType: "html",
      success: function (html) {
        $("#resultArea").html(html).removeClass("hidden");
        $("#printBtn").on("click", function () {
          window.print();
        });
        $btn.prop("disabled", false).text("Register");
        // regenerate membership id for next registration
        $("#membership_id").val(generateMembershipID());
        // scroll to result
        $("html,body").animate(
          { scrollTop: $("#resultArea").offset().top - 20 },
          400
        );
      },
      error: function (xhr) {
        alert("Submission failed. Check console or server logs.");
        console.error(xhr);
        $btn.prop("disabled", false).text("Register");
      },
    });
  });
});
