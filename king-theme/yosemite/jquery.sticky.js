jQuery(function(){
  var menuOffset = jQuery('.king-nav-top')[0].offsetTop;
  jQuery(document).bind('ready scroll',function() {
    var docScroll = jQuery(document).scrollTop();
    if(docScroll >= menuOffset) {
      jQuery('.king-headerf').addClass('fixed');
    } else {
      jQuery('.king-headerf').removeClass('fixed');
    }
   });
});