$(function() {

  $('.word').hover(
    function(){$(this).css('text-decoration', 'underline');},
    function(){$(this).css('text-decoration', 'inherit');}
  );

  $('.word').click(function(){
    $('.word').css('background-color', 'inherit');
    $(this).css('background-color', 'yellow');
    var ref = 'http://faclair.ac.uk/' + $(this).attr('data-lemma');
    showEntry(ref);

  });
});

function showEntry(ref) {
  var url = 'http://localhost:3030/Germanica?output=json&query=';
  var query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \
  SELECT ?hw ?en ?pid ?phw ?cid ?chw \
  WHERE \
  { \
  <'+ ref + '> rdfs:label ?hw . \
  OPTIONAL { <'+ ref + '> <http://faclair.ac.uk/meta/sense> ?en . } \
  OPTIONAL { <'+ ref + '> <http://faclair.ac.uk/meta/part> ?pid . ?pid rdfs:label ?phw . } \
  OPTIONAL { ?cid <http://faclair.ac.uk/meta/part> <'+ ref + '> . ?cid rdfs:label ?chw . } \
  }';
  query = encodeURIComponent(query);
  url += query;
  //alert(url);
  $.ajax({
      url: url
  }).done(function(data) {
    var results = data.results;
    var bindings = results.bindings;
    var output = "";
    output += "<h1>" + bindings[0].hw.value + "</h1>";
    var ens = [];
    $.each(bindings, function(k,v) {
      if (!ens.includes(v.en.value)) {
        ens.push(v.en.value);
      }
    });
    output += "<p>";
    ens.forEach(function(i){
      output += i + ', ';
    });
    output = output.substring(0,output.length-2);
    output += "</p>";
    var parts = [];
    $.each(bindings, function(k,v) {
      var found = false;
      parts.forEach(function(p) {
        if (p.id == v.pid.value) {
          found = true;
          return;
        }
      });
      if (!found) {
        try {
          parts.push({id: v.pid.value , hw: v.phw.value});
        }
        catch (err) {

        }
      }
    });
    if (parts.length>0) {
      output += "<p>";
      parts.forEach(function(i){
        output += '<a href="#" onclick="showEntry(\'' + i.id + '\')">' + i.hw + ', ';
      });
      output = output.substring(0,output.length-2);
      output += "</p>";
    }
    /*
    output += "<p>";
    $.each(bindings, function(key, value) {
      output += '<a href="#" onclick="showEntry(\'' + value.pid.value + '\')">' + value.phw.value + ', ';
    });
    output += "</p>";
    output += "<p>";
    $.each(bindings, function(key, value) {
      output += value.chw.value + ', ';
    });
    output += "</p>";
    */
    $('#rhs').html(output);
  });

}
