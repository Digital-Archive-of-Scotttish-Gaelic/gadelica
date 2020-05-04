$(function () {
  $('.slip').on('click', function () {
    var html = 'data-id : ' + $(this).attr('data-id') + '<br>';
    html += 'data-xml : ' + $(this).attr('data-xml') + '<br>';
    html += 'data-uri : ' + $(this).attr('data-uri');
    $('#info').html(html);
    $('#info').show();
  });

  $('#info').on('click', function() {
    $('#info').hide();
  });

  $('#wordformRadio').on('click', function () {
    $('#wordformOptions').show();
  });

  $('#headwordRadio').on('click', function () {
    $('#wordformOptions').hide();
  });
});