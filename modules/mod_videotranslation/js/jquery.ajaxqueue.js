//This handles the queues    
(function($) {

  var ajaxQueue = $({});

  $.ajaxQueue = function(ajaxOpts) {

    var oldComplete = ajaxOpts.complete;

    ajaxQueue.queue(function(next) {

      ajaxOpts.complete = function() {
        if (oldComplete) oldComplete.apply(this, arguments);

        next();
      };

      $.ajax(ajaxOpts);
    });
  };

})(jQuery);