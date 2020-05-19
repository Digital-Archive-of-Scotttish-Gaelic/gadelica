$(function () {

  $('[data-toggle="tooltip"]').tooltip();

  //bind the tooltips to the body for AJAX content
  $('body').tooltip({
    selector: '[data-toggle=tooltip]'
  });

  $(document).on('click', '.slipLink', function () {
    //reset the slip form
    resetSlip();
    var filename    = $(this).attr('data-xml');
    var id          = $(this).attr('data-id');
    var headword    = $(this).attr('data-headword');
    var pos         = $(this).attr('data-pos');
    $('#slipFilename').html(filename);
    $('#slipId').html(id);
    $('#slipHeadword').html(headword);
    $('#slipPOS').html(pos);

    //temp code
    $.getJSON('ajax.php?action=loadSlip&filename='+filename+'&id='+id
      +'&preContextScope='+$('#slipContext').attr('data-precontextscope')
      +'&postContextScope='+$('#slipContext').attr('data-postcontextscope'), function (data) {
      if (data.isNew != true) {
        $('#slipContext').attr('data-precontextscope', data.preContextScope);
        $('#slipContext').attr('data-postcontextscope', data.postContextScope);
        if (data.starred == 1) {
          $('#slipStarred').prop('checked', true);
        }
        $('#slipTranslation').val(data.translation);
        $('#slipNotes').val(data.notes);
      }
    })
      .done(function () {
        writeSlipContext(filename, id);
      });

    //
    //writeSlipContext(filename, id);
  });

  $('.updateContext').on('click', function () {
    var preScope = $('#slipContext').attr('data-precontextscope');
    var postScope = $('#slipContext').attr('data-postcontextscope');
    var filename = $('#slipFilename').text();
    var id = $('#slipId').text();
    switch ($(this).attr('id')) {
      case "decrementPre":
        preScope--;
        if (preScope == 0) {
          $('#decrementPre').addClass("disabled");
        }
        break;
      case "incrementPre":
        preScope++;
        $('#decrementPre').removeClass("disabled");
        break;
      case "decrementPost":
        postScope--;
        if (postScope == 0) {
          $('#decrementPost').addClass("disabled");
        }
        break;
      case "incrementPost":
        postScope++;
        $('#decrementPost').removeClass("disabled");
        break;
    }
    $('#slipContext').attr('data-precontextscope', preScope);
    $('#slipContext').attr('data-postcontextscope', postScope);
    writeSlipContext(filename, id);
  });

  $('#saveSlip').on('click', function () {
    var starred = $('#slipStarred').prop('checked') ? 1 : 0;
    var translation = $('#slipTranslation').val();
    var notes = $('#slipNotes').val();
    $.post("ajax.php", {action: "saveSlip", filename: $('#slipFilename').text(), id: $('#slipId').text(),
      starred: starred, translation: translation, notes: notes, preContextScope: $('#slipContext').attr('data-precontextscope'),
      postContextScope: $('#slipContext').attr('data-postcontextscope')
        }, function (data) {
      console.log(data);        //TODO: add some response code on successful save
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
        html += '<td><small><a href="#" class="slipLink" data-uri="' + val.uri + '"';
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

  $(document).on('click', '#closeSlipLink', function() {
    $('#slip').hide();
  });

  $('#wordformRadio').on('click', function () {
    $('#wordformOptions').show();
  });

  $('#headwordRadio').on('click', function () {
    $('#wordformOptions').hide();
  });

  function writeSlipContext(filename, id) {
    var preScope  = $('#slipContext').attr('data-precontextscope');
    var postScope = $('#slipContext').attr('data-postcontextscope');
    $.getJSON("ajax.php?action=getContext&filename="+filename+"&id="+id+"&preScope="+preScope+"&postScope="+postScope, function (data) {

      //html += '<a href="#">more</a> ';
      var html = data.pre;
      html += ' <strong>' + data.word + '</strong> ';
      html += data.post;
      $('#slipContext').html(html);
      $('#slip').show();
    });
  }

  function resetSlip() {
    $('#slipContext').attr('data-precontextscope', 20);
    $('#slipContext').attr('data-postcontextscope', 20);
    $('#slipStarred').prop('checked', false);
    $('#slipTranslation').val('');
    $('#slipNotes').val('');
  }
});

