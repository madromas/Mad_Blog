jQuery(function(){
  var menuOffset = jQuery('.sharetop_fix')[0].offsetTop;
  jQuery(document).bind('ready scroll',function() {
    var docScroll = jQuery(document).scrollTop();
    if(docScroll >= menuOffset) {
      jQuery('.paylas').addClass('fixed');
    } else {
      jQuery('.paylas').removeClass('fixed');
    }
   });
});