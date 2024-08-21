$(document).ready(function(){
  $(".search-toggle").click(function(event){
    $("div.king-search").find('.king-search-field').focus();
  });
});
$(document).ready(function(){
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function (e) {
        $('.inputprev').attr('src', e.target.result);
      }
      
      reader.readAsDataURL(input.files[0]);
    }
  }
  
  $("#featured-image").change(function(){
    readURL(this);
  });
});
$(document).ready(function(){
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function (e) {
        $('#imgprev').attr('src', e.target.result);
      }
      
      reader.readAsDataURL(input.files[0]);
    }
  }
  
  $("#featured-image2").change(function(){
    readURL(this);
  });
});
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
$(document).ready(function(){
$(".king-remove").click(function(event) {
  event.preventDefault();
  $(this).parents('.submit-images').remove();
});
});