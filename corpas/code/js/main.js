$(function () {

  $('[data-toggle="tooltip"]').tooltip();
  //bind the tooltips to the body for AJAX content
  $('body').tooltip({
    selector: '[data-toggle=tooltip]'
  });

  /**
   * Login handlers
   */
  $('#email').change(function () {
    var email = $(this).children("option:selected").val();
    $.getJSON('ajax.php?action=getUsername&email='+email, function (data) {
      $('#selectedUser').text(data.firstname + ' ' + data.lastname);
    })
      .done(function () {
        $('#passwordLabel').show();
        $('#emailSelectContainer').hide();
        $('#passwordContainer').show();
        $('#login').removeClass('loginButton');
        $('#loginCancel').removeClass('loginButton');
      });
  });

  $('#loginCancel').on('click', function () {
    $('#email option:first').prop('selected',true);
    $('#emailSelectContainer').show();
    $('#passwordContainer').hide();
    $('#login').addClass('loginButton');
    $('#loginCancel').addClass('loginButton');
    $('.loginMessage').text('');
  });
  /** -- **/

  $(document).on('click', '.slipLink', function () {
    //reset the slip form
    resetSlip();
    //update link to view link
    $(this).removeClass('createSlipLink');
    $(this).html('view slip');
    var filename    = $(this).attr('data-xml');
    var filenameElems = filename.split('_');
    var textId = filenameElems[0];
    var id          = $(this).attr('data-id');
    var headword    = $(this).attr('data-headword');
    var pos         = $(this).attr('data-pos');
    var date        = $(this).attr('data-date');
    var title       = $(this).attr('data-title');
    var page        = $(this).attr('data-page');
    var index       = $(this).attr('data-resultindex');   //the index of the result in the results array
    var auto_id     = $(this).attr('data-auto_id');
    $('#slipTextNum').html('Text ' + textId);
    $('#slipFilename').val(filename);
    $('#slipId').val(id);
    $('#slipHeadword').html(headword);
    $('#slipDate').html(date);
    $('#slipTextRef').html(date + ' <span class="slipFooterTitle">' + title + '</span> ' + page);
    $('#slipPOS').val(pos);
    $('#auto_id').val(auto_id);
    $.getJSON('ajax.php?action=loadSlip&filename='+filename+'&id='+id+'&index='+index
      +'&preContextScope='+$('#slipContext').attr('data-precontextscope')+'&auto_id='+auto_id
      +'&postContextScope='+$('#slipContext').attr('data-postcontextscope') + '&pos=' + pos, function (data) {
      if (data.wordClass) {
        $('#slipHeadword').html(headword);
        $('#slipWordClass').html('(' + data.wordClass + ')');
      }
  //    if (data.isNew != true) {
        $('#slipNumber').html(data.auto_id);
        $('#slipContext').attr('data-precontextscope', data.preContextScope);
        $('#slipContext').attr('data-postcontextscope', data.postContextScope);
        if (data.starred == 1) {
          $('#slipChecked').html('Ch&check;');
        } else {
          $('#slipChecked').html('');
        }
        $('#slipTranslation').html(data.translation);
        $('#slipNotes').html(data.notes);
//      }
    })
      .done(function () {
        writeSlipContext(filename, id);
      });
  });

  $('.slipLink2').on('click', function () {
    if ($(this).attr("data-resultindex") != -1) {   //test for use in non-search results pages
      $(this).removeClass('createSlipLink');
      $(this).html('view slip');
    }
  });

  $('#slipModal').on('show.bs.modal', function (event) { // added by MM
    var modal = $(this);
    var slipLink = $(event.relatedTarget);
    var slipId = slipLink.data('auto_id');
    var headword = slipLink.data('headword');
    var pos = slipLink.data('pos');
    var id = slipLink.data('id');
    var xml = slipLink.data('xml');
    //var filenameElems = xml.split('_');
    var textId = xml.split('_')[0];
    var uri = slipLink.data('uri');
    var date = slipLink.data('date');
    var title = slipLink.data('title');
    var page = slipLink.data('page');
    var resultindex = slipLink.data('resultindex');
    var auto_id = slipLink.data('auto_id');
    var body = '';
    var header = headword;
    //write the hidden info needed for slip edit
    $('#slipFilename').val(xml);
    $('#slipId').val(id);
    $('#slipPOS').val(pos);
    $('#auto_id').val(auto_id);
    $('#slipHeadword').html(headword);
    //get the slip info from the DB
    $.getJSON('ajax.php?action=loadSlip&filename='+xml+'&id='+id+'&index='+resultindex
      +'&preContextScope='+$('#slipContext').attr('data-precontextscope')+'&auto_id='+auto_id
      +'&postContextScope='+$('#slipContext').attr('data-postcontextscope') + '&pos=' + pos, function (data) {
      if (data.wordClass) {
        var wc = data.wordClass;
        if (wc=='noun') {
          header += ' <em>n.</em>';
        }
        else if (wc=='verb') {
          header += ' <em>v.</em>';
        }
      }
      /*
      if (data.starred == 1) {
        html += 'Ch&check;<br>';
      }
      html += 'notes: ' + data.notes + '<br>';
      */
      var context = data.context.pre["output"] + ' <mark>' + data.context.word + '</mark> ' + data.context.post["output"];
      body += '<p>' + context + '</p>';
      body += '<p><small class="text-muted">' + data.translation + '</small></p>';
      //body += '<p class="small">[#' + textId + ': <em>' + title + '</em> p.' + page + ']</p>';
      body += '<p class="text-muted"><span data-toggle="tooltip" data-html="true" title="' + '<em>' + title + '</em> p.' + page + '">#' + textId + ': ' + date + '</span></p>';
      body += '<hr/>';
      body += '<ul class="list-inline">';
      $.each(data.categories, function (key, value) {
        body += '<li class="list-inline-item badge badge-success">' + value + '</li>';
      });
      body += '</ul><ul class="list-inline">';
      $.each(data.slipMorph, function(k, v) {
        body += '<li class="list-inline-item badge badge-secondary">' + v + '</li>';
      });
      body += '</ul>';
      slipId = data.auto_id;
    })
      .done(function () {
        modal.find('.modal-title').html(header);
        modal.find('#slipNo').text('§'+slipId);
        modal.find('.modal-body').html(body);
      });
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
        if ($(this).attr('href')) {
          preScope++;
          $('#decrementPre').removeClass("disabled");
        }
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
    $('#preContextScope').val(preScope);
    $('#postContextScope').val(postScope);
    writeSlipContext(filename, id);
    saveSlip();
  });

  $(document).on('click', '#editSlip', function () {
    $('#slipModal').modal('hide');
    var filename = $('#slipFilename').val();
    var id = $('#slipId').val();
    var headword = $('#slipHeadword').text();
    var pos = $('#slipPOS').val();
    var auto_id = $('#auto_id').val();
    var url = 'slipEdit.php?filename=' + filename + '&id=' + id + '&headword=' + headword;
    url += '&pos=' + pos + '&auto_id=' + auto_id;
    var win = window.open(url, '_blank');
    if (win) {
      //Browser has allowed it to be opened
      win.focus();
    } else {
      //Browser has blocked it
      alert('Please allow popups for this website');
    }
  });

  /*
      Load the dictionary results
   */
  $('.loadDictResults').on('click', function () {
    var formNum = $(this).attr('data-formNum');
    $('#form-' + formNum + ' tbody').empty();   //clear any previous results
    var locations  = $(this).attr('data-locs');
    var headword = $(this).attr('data-lemma');
    var pos = $(this).attr('data-pos');
    $.post("ajax.php", {action: "getDictionaryResults", locs: locations}, function (data)  {
      $.each(data, function (key, val) {
        var title = 'Headword: ' + headword + '<br>';
        title += 'POS: ' + pos + '<br>';
        title += 'Date: ' + val.date + '<br>';
        title += 'Title: ' + val.title + '<br>';
        title += 'Page No:: ' + val.page + '<br><br>';
        title += val.filename + '<br>' + val.id;
        var slipLinkText = 'create slip';
        var createSlipStyle = 'createSlipLink';
        if (val.auto_id) {  //if a slip exists for this entry
          slipLinkText = 'view slip';
          createSlipStyle = '';
        }
        html = '<tr>';
        html += '<td style="text-align: right;">'+val.pre.output + '</td>';
        html += '<td><a href="viewText.php?uri=' + val.uri + '&id=' + val.id + '"';
        html += ' data-toggle="tooltip" data-html="true" title="' + title + '">';
        html += val.word + '</a>';
        html += '<td>' + val.post.output + '</td>';
        html += '<td><small><a href="#" class="slipLink ' + createSlipStyle + '" data-uri="' + val.uri + '"';
        html += ' data-headword="' + headword + '" data-pos="' + pos + '"';
        html += ' data-id="' + val.id + '" data-xml="' + val.filename + '"';
        html += ' data-date="' + val.date + '" data-title="' + val.title + '" data-page="' + val.page + '"';
        html += ' data-auto_id="' + val.auto_id + '"';
        html += '>' + slipLinkText + '</a></small>';
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

  $('.windowClose').on('click', function () {
    window.close();
  });

  $('#savedClose').on('click', function () {
    saveSlip();
    $('#slipSavedModal').modal();
    setTimeout(function() {
      window.close();
    }, 2000);
  });

  function writeSlipContext(filename, id) {
    var html = '';
    var preScope  = $('#slipContext').attr('data-precontextscope');
    var postScope = $('#slipContext').attr('data-postcontextscope');
    $.getJSON("ajax.php?action=getContext&filename="+filename+"&id="+id+"&preScope="+preScope+"&postScope="+postScope, function (data) {
      var preOutput = data.pre["output"];
      var postOutput = data.post["output"];
      //handle zero pre/post context sizes
      if (typeof preOutput == "undefined") {
        preOutput = "";
        $('#decrementPre').removeAttr("href");
      } else {
        $('#decrementPre').attr("href", "#");
      }
      if (typeof postOutput == "undefined") {
        postOutput = "";
        $('#decrementPost').removeAttr("href");
      } else {
        $('#decrementPost').attr("href", "#");
      }
      //handle reaching the start/end of the document
      if (data.prelimit) {
        $('#incrementPre').removeAttr("href");
      } else {
        $('#incrementPre').attr("href", "#");
      }
      if (data.postlimit) {
        $('#incrementPost').removeAttr("href");
      } else {
        $('#incrementPost').attr("href", "#");
      }
      html = preOutput;
      if (data.pre["endJoin"] != "right" && data.pre["endJoin"] != "both") {
        html += ' ';
      }
      //html += '<span id="slipWordInContext">' + data.word + '</span>';
      html += '<mark id="slipWordInContext">' + data.word + '</mark>'; // MM
      if (data.post["startJoin"] != "left" && data.post["startJoin"] != "both") {
        html += ' ';
      }
      html += postOutput;
      $('#slipContext').html(html);
      $('#slip').show();
    });
  }

  function resetSlip() {
    $('#slipNumber').html('');
    $('#slipContext').attr('data-precontextscope', 20);
    $('#slipContext').attr('data-postcontextscope', 20);
    $('#slipStarred').prop('checked', false);
    $('#slipTranslation').html('');
    $('#slipNotes').html('');
  }

  function saveSlip() {
    var starred = $('#slipStarred').prop('checked') ? 1 : 0;
    var translation = CKEDITOR.instances['slipTranslation'].getData();
    var notes = CKEDITOR.instances['slipNotes'].getData();
    var data = {action: "saveSlip", filename: $('#slipFilename').text(), id: $('#slipId').text(),
      auto_id: $('#auto_id').val(), pos: $('#pos').val(), starred: starred, translation: translation,
      notes: notes, preContextScope: $('#slipContext').attr('data-precontextscope'),
      postContextScope: $('#slipContext').attr('data-postcontextscope'), wordClass: $('#wordClass').val()};
    if ($('#wordClass').val() == "noun") {      //noun data
      data['numgen'] = $('#posNumberGender').val();
      data['case'] = $('#posCase').val();
    } else {                                    //verb data
      var mode = $('#posMode').val();
      data['mode'] = mode;
      if (mode == "imperative") {
        data['imp_person'] = $('#posImpPerson').val();
        data['imp_number'] = $('#posImpNumber').val();
      } else if (mode == "finite") {
        data['fin_person'] = $('#posFinPerson').val();
        data['fin_number'] = $('#posFinNumber').val();
        data['status'] = $('#posStatus').val();
        data['tense'] = $('#posTense').val();
        data['mood'] = $('#posMood').val();
      }
    }
    $.post("ajax.php", data, function (response) {
      console.log(response);        //TODO: add some response code on successful save
    });
  }
});
