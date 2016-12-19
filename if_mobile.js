(function($){
  let ifMobile = function (device, mobile) {
    device.Android = mobile.match(/Android/i);
    device.iOS = mobile.match(/iPhone|iPad|iPod/i);
    device.any = device.Android || device.iOS;
    device.selector = {
      hide: '.on-desktop',
      show: '.on-mobile'
    };
    device.show = function(){
      $(device.selector.hide).hide();
      $(device.selector.show).show();
    };
    device.hide = function(){
      $(device.selector.show).hide();
      $(device.selector.hide).show();
    };
    device.toggle = function(clause){
      clause = typeof clause !== 'boolean' ? device.any : clause;
      if (clause) device.show();
      else device.hide();
    };
    return device;
  }({}, navigator.userAgent);
})(jQuery);
