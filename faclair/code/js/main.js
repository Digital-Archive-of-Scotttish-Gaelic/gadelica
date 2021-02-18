$(function() {
  var searchTerm = removeAccents($('#searchBox').val());
  if (searchTerm!='') {
    $('#resultsTable tbody').empty();
    var lang = 'en';
    if ($('#gdRadio').is(':checked')) { lang = 'gd'; }
    var snh = false;
    var frp = false;
    var seotal = false;
    var dwelly = false;
    var others = false;
    if($('#snhCheck').is(':checked')) { snh = true; }
    if($('#frpCheck').is(':checked')) { frp = true; }
    if($('#seotalCheck').is(':checked')) { seotal = true; }
    var parameters = '&searchTerm='+searchTerm;
    if (lang=='en') { parameters += '&gd=no'; }
    else { parameters += '&gd=yes'; }
    if (snh) { parameters += '&lex=snh'; }
    if (frp) { parameters += '&lex=frp'; }
    if (seotal) { parameters += '&lex=seotal'; }
    if (lang == 'en') {
      var url = 'getResults.php?action=getEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
      $.getJSON(url, function(data) {
        addData(data,parameters);
      }).done(function() {
        var url = 'getResults.php?action=getMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
        $.getJSON(url, function(data) {
          addData(data,parameters);
        }).done(function() {
          var url = 'getResults.php?action=getEvenMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
          $.getJSON(url, function(data) {
            addData(data,parameters);
          }).done(function() {
            var url = 'getResults.php?action=getEvenEvenMoreEnglishResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
            $.getJSON(url, function(data) {
              addData(data,parameters);
            }).done(noResults());
          });
        });
      });
    }
    else {
      var url = 'getResults.php?action=getGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
      $.getJSON(url, function(data) {
        addData(data,parameters);
      }).done(function() {
        var url = 'getResults.php?action=getMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
        $.getJSON(url, function(data) {
          addData(data,parameters);
        }).done(function() {
          var url = 'getResults.php?action=getEvenMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
          $.getJSON(url, function(data) {
            addData(data,parameters);
          }).done(function() {
            var url = 'getResults.php?action=getEvenEvenMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal+'&dwelly='+dwelly+'&others='+others;
            $.getJSON(url, function(data) {
              addData(data,parameters);
            }).done(noResults());
          });
        });
      });
    }
  }
});

function removeAccents(str) {
  str = str.replace('ù','u');
  str = str.replace('è','e');
  str = str.replace('é','e');
  str = str.replace('à','a');
  str = str.replace('ì','i');
  str = str.replace('ò','o');
  str = str.replace('ó','o');
  return str;
}

function addData(data,parameters) { // add rows (search results) to the table
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
    $('#resultsTable tbody').append('<tr><td><a href="viewEntry.php?id=' + encodeURI(id) + parameters +'">' + hwStr + '</a></td><td>' + enStr + '</td></tr>');
  });
}

function noResults() {
  x = $('#resultsTable tbody tr').length;
  if (x==0) {
    alert('No results!'); // NEED GAELIC
  }
}