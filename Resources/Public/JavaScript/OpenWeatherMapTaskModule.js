/**
 * Module: TYPO3/CMS/Weather2/OpenWeatherMapTaskModule
 * JavaScript to show/hide fields in scheduler
 */
define('TYPO3/CMS/Weather2/OpenWeatherMapTaskModule', ['jquery', 'jquery/autocomplete'], function ($) {
  $(document).ready(function () {
    $('#recordStoragePage').change(function () {
      $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });


    $('#query').autocomplete({
      onSearchStart: function (params) {
        params.apiKey = $('#apiKey').val();
      },
      serviceUrl: TYPO3.settings.ajaxUrls['weather2_owm_geocode'],
      dataType: 'json',
      minChars: 3,
      onSelect: function (suggestion) {

      }
    }).keypress(function (e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if (code === 13) {
        return false;
      }
    });


    var errorNotificationFields = ['mailConfig_row', 'emailSenderName_row', 'emailSender_row', 'emailReceiver_row'];

    $('#errorNotification').click(function () {
      toggleErrorNotificationFields();
    });

    toggleErrorNotificationFields();

    function toggleErrorNotificationFields() {
      if ($('#errorNotification').is(':checked')) {
        setDisplayAttributeOfElements('', errorNotificationFields);
      } else {
        setDisplayAttributeOfElements('none', errorNotificationFields);
      }
    }

    /**
     * Sets the display property for each element in array elements
     *
     * @param display display property value from css (e.g. block or none)
     * @param elements array of all elements ['first_element', 'second_element']
     */
    function setDisplayAttributeOfElements(display, elements) {
      $(elements).each(function (index, value) {
        $('#' + value).css('display', display);
      });
    }
  });
});