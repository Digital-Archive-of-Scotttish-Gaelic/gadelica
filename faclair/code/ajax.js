$(function() {

// still need to get Gaelic search working

  $('#searchForm').submit(function(e){
    event.preventDefault();
    $('#resultsTable tbody').empty();
    var searchTerm = $('#searchBox').val();
    var lang = 'en';
    if ($('#gdRadio:checked').val()=='gd' ) { lang = 'gd'; }
    var snh = false;
    var frp = false;
    var seotal = false;
    var dwelly = false;
    var others = false;
    if ($('#snhCheck:checked').val()=='yes' ) { snh = true; }
    if ($('#frpCheck:checked').val()=='yes' ) { frp = true; }
    if ($('#seotalCheck:checked').val()=='yes' ) { seotal = true; }
    if ($('#dwellyCheck:checked').val()=='yes' ) { dwelly = true; }
    if ($('#othersCheck:checked').val()=='yes' ) { others = true; }
    if (lang == 'en') {
      var url = 'ajax.php?action=getEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
      $.getJSON(url, function(data) {
        addData(data);
      }).done(function() {
        $('#resultsTable tbody').append('<tr><td></td><td></td></tr>');
        var url2 = 'ajax.php?action=getMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
        $.getJSON(url2, function(data2) {
          addData(data2);
        }).done(function() {
          $('#resultsTable tbody').append('<tr><td></td><td></td></tr>');
          var url3 = 'ajax.php?action=getEvenMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
          $.getJSON(url3, function(data3) {
            addData(data3);
          }).done(function() {
            $('#resultsTable tbody').append('<tr><td></td><td></td></tr>');
            var url4 = 'ajax.php?action=getEvenEvenMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
            $.getJSON(url4, function(data4) {
              addData(data4);
            });
          });
        });
      });
    }
    else {
      var url = 'ajax.php?action=getGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
      $.getJSON(url, function(data) {
        addData(data);
      }).done(function() {
        $('#resultsTable tbody').append('<tr><td></td><td></td></tr>');
        var url2 = 'ajax.php?action=getMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
        $.getJSON(url2, function(data) {
          addData(data);
        }).done(function() {

        });
      });
    }
  });

});

function addData(data) {
  var ids = [];
  $.each(data, function(k,v) {
    id = v.id.value;
    if (ids.indexOf(id)<0) { // unique values only
      ids.push(id);
    }
  });
  $.each(ids, function(k,id) { // display each entry in a row
    var hws = [];
    $.each(data, function(k,v) {
      var gd = v.gd.value;
      if (v.id.value == id && hws.indexOf(gd)<0) { // unique
        hws.push(gd);
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
    var hwStr;
    if (hws.length>0) {
      hwStr = hws.join(', ');
    }
    else { hwStr = id; }
    $('#resultsTable tbody').append('<tr><td><a href="viewEntry.php?id=' + encodeURI(id) + '">' + hwStr + '</a></td><td>' + enStr + '</td></tr>');
  });
}
