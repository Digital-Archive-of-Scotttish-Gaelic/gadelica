$(function () {
  $('.slip').on('click', function () {
    $.getJSON("ajax.php?action=getContext&filename="+$(this).attr('data-xml')+"&id="+$(this).attr('data-id'), function (data) {
      $.each(data, function (key, val) {
        var html = data.pre;
        html += ' <strong>' + data.word[0] + '</strong> ';
        html += data.post;
        $('#info').html(html);
        $('#info').show();
      });
    });
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