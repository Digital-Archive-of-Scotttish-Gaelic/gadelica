$(function () {
  $('[data-toggle="tooltip"]').tooltip()

  $('.slip').on('click', function () {
    var filename  = $(this).attr('data-xml');
    var id        = $(this).attr('data-id');
    var headword  = $(this).attr('data-headword');
    var pos       = $(this).attr('data-pos');
    $.getJSON("ajax.php?action=getContext&filename="+filename+"&id="+id, function (data) {
      $.each(data, function (key, val) {
        var html = '<em>filename:</em> ' + filename + '<br><em>id:</em> ' + id + '<br>';
        html += '<em>headword:</em> ' + headword + '<br>';
        html += '<em>POS:</em> ' + pos + '<br><br>';
        html += data.pre;
        html += ' <strong>' + data.word[0] + '</strong> ';
        html += data.post;
        $('#info').html(html);
        $('#info').show();
      });
    });
  });

  $('.loadDictResults').on('click', function () {
    //check if already loaded
    if ($('#form-' + formNum).contents().length != 0) {
      $('#form-' + formNum).show();
      return
    }
    var locations  = $(this).attr('data-locs');
    var formNum = $(this).attr('data-formNum');
    var html = '<table><tbody>';
    $.getJSON("ajax.php?action=getDictionaryResults&locs=" + locations, function (data) {
      $.each(data, function (key, val) {
        html += '<tr>';
        html += '<td style="text-align: right;">'+val.pre + '</td>';
        html += '<td>' + val.word[0] + '</td>';
        html += '<td>' + val.post + '</td>';
        html += '</tr>';
      });
    })
      .done(function () {
        html += '</tbody></table>';
        $('#form-' + formNum).html(html);
        $('#form-' + formNum).show();
        $('#show-' + formNum).hide();
        $('#hide-' + formNum).show();
      });
  });

  $('.hideDictResults').on('click', function () {
    var formNum = $(this).attr('data-formNum');
    $('#show-' + formNum).show();
    $('#hide-' + formNum).hide();
    $('#form-' + formNum).hide();
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