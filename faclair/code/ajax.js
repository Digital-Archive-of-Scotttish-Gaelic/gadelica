$(function() {

  $('#searchForm').submit(function(e){
    event.preventDefault();
    $('#resultsTable tbody').empty();
    var searchTerm = $('#searchBox').val();
    var lang = 'en';
    if ($('#gdRadio:checked').val()=='gd' ) { lang = 'gd'; }
    var snh = false;
    var frp = false;
    var seotal = false;
    if ($('#snhCheck:checked').val()=='yes' ) { snh = true; }
    if ($('#frpCheck:checked').val()=='yes' ) { frp = true; }
    if ($('#seotalCheck:checked').val()=='yes' ) { seotal = true; }
    if (lang == 'en') {
      var url = 'ajax.php?action=getEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal;
      $.getJSON(url, function(data) {
        addData(data);
      }).done(function() {
        $('#resultsTable tbody').append('<tr><td></td><td></td></tr>');
        var url2 = 'ajax.php?action=getMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal;
        $.getJSON(url2, function(data2) {
          addData(data2);
        });
      });
    }
    else {
      var url = 'ajax.php?action=getGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal;
      alert(url);

    }




  });

/*

    if (lang == 'en') {
      $.getJSON('ajax.php?action=getEnglishResults&searchTerm='+search+'&snh='+snh+'&frp='+frp+'&seotal='+seotal, function(data) {
        alert(data);
        addData(data);
      }).done(function() {

        $('#resultsTable tbody').append('<tr><td>dun</td><td>dun</td></tr>');
        $.getJSON('ajax.php?action=getMoreEnglishResults&searchTerm='+search, function(data) {
          addData(data);
        });

      });
    }
    else {
      $.getJSON('ajax.php?action=getGaelicResults&searchTerm='+search+'&snh='+snh+'&frp='+frp+'&seotal='+seotal, function(data) {
        addData(data);
      }).done(function() {

        $('#resultsTable tbody').append('<tr><td>dun</td><td>dun</td></tr>');
        $.getJSON('ajax.php?action=getMoreGaelicResults&searchTerm='+search, function(data) {
          addData(data);
        });

      });
    }
  }
  */

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
