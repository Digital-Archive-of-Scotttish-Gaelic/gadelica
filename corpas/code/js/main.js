$(function () {
  $('[data-toggle="tooltip"]').tooltip();

  //bind the tooltips to the body for AJAX content
  $('body').tooltip({
    selector: '[data-toggle=tooltip]'
  });

  $(document).on('click', '.slip', function () {
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
        html += ' <strong>' + data.word + '</strong> ';
        html += data.post;
        $('#info').html(html);
        $('#info').show();
      });
    });
  });

  $('.loadDictResults').on('click', function () {
    var formNum = $(this).attr('data-formNum');
    var locations  = $(this).attr('data-locs');
    var headword = $(this).attr('data-lemma');
    var pos = $(this).attr('data-pos');
    $.post("ajax.php", {action: "getDictionaryResults", locs: locations}, function (data)  {
      $.each(data, function (key, val) {
        var title = val.filename + val.id + '<br><br>';
        title += 'headword: ' + headword + '<br>';
        title += 'POS: ' + pos;

        html = '<tr>';
        html += '<td style="text-align: right;">'+val.pre + '</td>';
        html += '<td><a href="viewText.php?uri=' + val.uri + '&id=' + val.id + '"';
        html += ' data-toggle="tooltip" data-html="true" title="' + title + '">';
        html += val.word + '</a>';
        html += '<td>' + val.post + '</td>';
        html += '<td><small><a href="#" class="slip" data-uri="' + val.uri + '"';
        html += ' data-headword="' + headword + '" data-pos="' + pos + '"';
        html += ' data-id="' + val.id + '" data-xml="' + val.filename + '">slip</a></small>';
        html += '</td>';
        html += '</tr>';
        $('#form-' + formNum + ' tbody').append(html);
      });
    }, "json")
      .done(function () {
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

