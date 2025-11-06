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