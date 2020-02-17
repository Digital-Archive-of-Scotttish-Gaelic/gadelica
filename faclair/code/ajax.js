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
        /*
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
        */
      });
    }
    else {
      var url = 'ajax.php?action=getGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal;
      $.getJSON(url, function(data) {
        addData(data);
      }).done(function() {
        $('#resultsTable tbody').append('<tr><td></td><td></td></tr>');
        var url2 = 'ajax.php?action=getMoreGaelicResults&searchTerm='+searchTerm+'&snh='+snh+'&frp='+frp+'&seotal='+seotal;
        $.getJSON(url2, function(data) {
          addData(data);
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
      alert(id);
    }
  });
  $.each(ids, function(k,id) { // display each entry in a row
    var hw = '';
    $.each(data, function(k,v) {
      // prioritise headwords from the general list, if any
      if (v.id.value == id && v.gdlex.value == 'http://faclair.ac.uk/sources/general') {
        hw = v.gd.value;
        return;
      }
    });
    if (hw=='') {
      // otherwise just use first headword you find
      $.each(data, function(k,v) {
        if (v.id.value == id) {
          hw = v.gd.value;
          return;
        }
      });
    }
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
