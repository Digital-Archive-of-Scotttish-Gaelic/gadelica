$(function() {

  var search = $('body').attr('data-searchterm');
  if (typeof search != 'undefined') {
    $('#searchBox').val(search);
    var lang = $('body').attr('data-lang');
    if(lang == 'en') {
      $.getJSON('ajax.php?action=getEnglishResults&searchTerm='+search, function(data) {
        addData(data);
      }).done(function() {
        $('#resultsTable tbody').append('<tr><td>dun</td><td>dun</td></tr>');
        $.getJSON('ajax.php?action=getMoreEnglishResults&searchTerm='+search, function(data) {
          addData(data);
        });
      });
    }
    else {
      $.getJSON('ajax.php?action=getGaelicResults&searchTerm='+search, function(data) {
        addData(data);
      }).done(function() {
        $('#resultsTable tbody').append('<tr><td>dun</td><td>dun</td></tr>');
        $.getJSON('ajax.php?action=getMoreGaelicResults&searchTerm='+search, function(data) {
          addData(data);
        });
      });
    }
  }

});

function addData(data) {
  var ids = [];
  var hw;
  $.each(data, function(k,v) {
    //$('#resultsTable tbody').append('<tr><td><a href="viewEntry.php?id=' + encodeURI(v.id.value) + '">' + v.gd.value + '</a></td><td>' + v.en.value + '</td></tr>');
    id = v.id.value;
    if (ids.indexOf(id)<0) {
      ids.push(id);
    }
  });
  $.each(ids, function(k,id) {
    $.each(data, function(k,v) {
      if (v.id.value == id) {
        hw = v.gd.value;
        return;
      }
    });
    var ens = [];
    $.each(data, function(k,v) {
      var en = v.en.value;
      if (v.id.value == id && ens.indexOf(en)<0) {
        ens.push(en);
      }
    });
    var enStr = ens.join(', ');
    $('#resultsTable tbody').append('<tr><td><a href="viewEntry.php?id=' + encodeURI(id) + '">' + hw + '</a></td><td>' + enStr + '</td></tr>');
  });
}
