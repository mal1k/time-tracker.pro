(function($) {
  "use strict";

  /*----------------------
      Daterange Active
    -------------------------*/
  $('#daterange').daterangepicker({
      "alwaysShowCalendars": true,
      "startDate": "01/28/2021",
      "endDate": "02/03/2021"
    }, function(start, end, label) {
  });

})(jQuery);