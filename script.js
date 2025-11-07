$(function(){
  function setError(elem,msg){
    elem.addClass('input-error');
    $('[data-for="'+elem.attr('id')+'"]').text(msg || 'Required');
  }
  function clearError(elem){
    elem.removeClass('input-error');
    $('[data-for="'+elem.attr('id')+'"]').text('');
  }

  // basic validation on submit
  $('#regForm').on('submit', function(e){
    var valid = true;
    // validate required inputs
    $('#regForm [required]').each(function(){
      var $this = $(this);
      if(!$this.val() || $this.val().trim()===''){
        setError($this,'This field is required');
        valid = false;
      } else {
        // field-specific checks
        if($this.attr('type')==='email'){
          var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
          if(!re.test($this.val())){ setError($this,'Invalid email address'); valid = false; } else clearError($this);
        } else if($this.attr('id')==='phone'){
          // digits only check (allow + and spaces)
          var phoneDigits = $this.val().replace(/[^0-9]/g,'').length;
          if(phoneDigits < 7){ setError($this,'Enter a valid phone number'); valid = false; } else clearError($this);
        } else {
          clearError($this);
        }
      }
    });

    if(!valid){ e.preventDefault(); return false; }
    // allow submit - show small waiting message
    $('#submitBtn').text('Registering...').attr('disabled',true);
  });

  // clear error on input
  $('#regForm input, #regForm select').on('input change', function(){
    clearError($(this));
  });
});

  // EmailJS config — paste your real IDs here
const EMAILJS_SERVICE = "service_79qn2m4";     // from your Service (you already have this)
const EMAILJS_TEMPLATE = "template_xxx123";    // put the Template ID you saved
const EMAILJS_PUBLIC_KEY = "YOUR_PUBLIC_KEY";  // from EmailJS -> Account -> API Keys

// initialize (if not initialized in <head>)
emailjs.init(EMAILJS_PUBLIC_KEY);

// capture & send membership card
function sendMembershipCard(toName, toEmail, memberId) {
  const cardEl = document.querySelector('.card-membership') || document.querySelector('.card');
  if (!cardEl) return alert("Card element not found");

  html2canvas(cardEl, { scale: 0.8, useCORS: true }).then(canvas => {
    const dataURL = canvas.toDataURL('image/png');

    const templateParams = {
      to_name: toName,
      to_email: toEmail,
      member_id: memberId,
      membership_attachment: [
        {
          data: dataURL,
          name: 'membership-card.png'
        }
      ]
    };

    emailjs.send(EMAILJS_SERVICE, EMAILJS_TEMPLATE, templateParams)
      .then(response => {
        console.log('Email sent', response);
        alert('Membership card emailed successfully!');
      })
      .catch(err => {
        console.error('EmailJS error', err);
        alert('Failed to send email. Check console for errors.');
      });
  });
}
