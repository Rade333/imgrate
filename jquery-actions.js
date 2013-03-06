$(document).ready(function() {

  $(".message .close").click(function() 
  {
    $(".message").animate(
    {
      "opacity": "0"
    }, "slow");
    $(".message").slideUp();
  });

});

