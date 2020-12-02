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

  /**
   * Send email to request slip unlock
   */
  $('#lockedBtn').on('click', function () {
    var owner = $(this).attr('data-owner');
    var slipId = $(this).attr('data-slipid');
    $.ajax({url: 'ajax.php?action=requestUnlock&slipId='+slipId+'&owner='+owner})
      .done(function () {
        alert('email sent');
      });
  });

  /**
   * Load and display slip data in a modal
   */
  $('#slipModal').on('show.bs.modal', function (event) { // added by MM
    var modal = $(this);
    var slipLink = $(event.relatedTarget);
    //reset lock buttons
    $('.lockBtn').addClass('d-none');
    $('#lockedBtn').attr('title', 'Slip is locked - click to request unlock');
    $('#lockedBtn').removeClass('disabled');
    var locked = "";
    var owner = "";
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
    var canEdit;
    var isOwner;
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
      //check if user can edit slip
      canEdit = data.canEdit ? true : false;
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
      //check the slip lock status
      locked = data.locked;
      owner = data.owner;
      isOwner = data.isOwner;
    })
      .done(function () {
        modal.find('.modal-title').html(header);
        modal.find('#slipNo').text('§'+slipId);
        $('#auto_id').val(slipId);
        modal.find('.modal-body').html(body);
        /*if (canEdit) {
          $('.modal').find('button#editSlip').prop('disabled', false);
        } else {
          $('.modal').find('button#editSlip').prop('disabled', 'disabled');
        }*/
        //show the correct lock icon
        /*
        if (locked == 1) {
          $('.locked').removeClass('d-none');
          $('.locked').attr('data-owner', owner);
          $('.locked').attr('data-slipid', slipId);
          if (isOwner) {
            $('#lockedBtn').attr('title', 'Slip is locked');
            $('#lockedBtn').addClass('disabled');
          }
        } else {
          $('.unlocked').removeClass('d-none');
        }
        */
      });
  });

  $('.updateContext').on('click', function () {
    var preScope = $('#slipContext').attr('data-precontextscope');
    var postScope = $('#slipContext').attr('data-postcontextscope');
    var filename = $('#slipFilename').text();
    var id = $('#wordId').text();
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
  });

  $(document).on('click', '#editSlip', function () {
    $('#slipModal').modal('hide');
    var filename = $('#slipFilename').val();
    var id = $('#slipId').val();
    var headword = $('#slipHeadword').text();
    var pos = $('#slipPOS').val();
    var auto_id = $('#auto_id').val();
    var url = '?m=collection&a=edit&id=' + auto_id + '&filename=' + filename + '&&headword=' + headword;
    url += '&pos=' + pos + '&wid=' + id;
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
        var slipClass = 'editSlipLink';
        var slipLinkText = 'create slip';
        var createSlipStyle = 'createSlipLink';
        var slipUrl = '?m=slip&filename='+val.filename+'&id='+val.id+'&headword='+headword+'&pos='+pos+'&auto_id='+val.auto_id;
        if (val.auto_id) {    //if a slip exists for this entry
          slipLinkText = 'view slip';
          slipClass = 'slipLink2';
          createSlipStyle = '';
          slipUrl = '#';
        }
        html = '<tr>';
        html += '<td style="text-align: right;">'+val.pre.output + '</td>';
        html += '<td><a href="?m=text&a=view&uri=' + val.uri + '&id=' + val.id + '"';
        html += ' data-toggle="tooltip" data-html="true" title="' + title + '">';
        html += val.word + '</a>';
        html += '<td>' + val.post.output + '</td>';
        html += '<td><small><a href="'+slipUrl+'" target="_blank" class="' + slipClass + ' ' + createSlipStyle + '" data-uri="' + val.uri + '"';
        if (slipClass == 'slipLink2') {   //only use the modal for existing slips
          html += ' data-toggle="modal" data-target="#slipModal" ';
        }
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
    var wordclass = $('#wordClass').val();
    var starred = $('#slipStarred').prop('checked') ? 1 : 0;
    var locked = $('#locked').val();
    var translation = CKEDITOR.instances['slipTranslation'].getData();
    var notes = CKEDITOR.instances['slipNotes'].getData();
    var data = {action: "saveSlip", filename: $('#slipFilename').text(), id: $('#wordId').text(),
      auto_id: $('#auto_id').text(), pos: $('#pos').val(), starred: starred, translation: translation,
      notes: notes, preContextScope: $('#slipContext').attr('data-precontextscope'),
      postContextScope: $('#slipContext').attr('data-postcontextscope'), wordClass: wordclass,
      locked: locked};
    console.log(data);
    switch (wordclass) {
      case "noun":
        data['numgen'] = $('#posNumberGender').val();
        data['case'] = $('#posCase').val();
        break;
      case "verb":
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
        break;
      case "preposition":
          data["prep_mode"] = $('#posPrepMode').val();
          if (data["prep_mode"] == 'conjugated' || data["prep_mode"] == 'possessive') {
            data["prep_person"] = $('#posPrepPerson').val();
            data["prep_number"] = $('#posPrepNumber').val();
            if (data["prep_person"] == 'third person' && data["prep_number"] == 'singular') {
              data["prep_gender"] = $('#posPrepGender').val();
            }
          }
        break;
    }
    $.post("ajax.php", data, function (response) {
      console.log(response);        //TODO: add some response code on successful save
    });
  }
});
