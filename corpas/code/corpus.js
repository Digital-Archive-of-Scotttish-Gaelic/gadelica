$(function() {

  $('.word').hover(
    function(){$(this).css('text-decoration', 'underline');},
    function(){$(this).css('text-decoration', 'inherit');}
  );
  
  $('.word').click(function(){
    $('.word').css('background-color', 'inherit');
    $(this).css('background-color', 'yellow');
    var ref = 'http://faclair.ac.uk/' + $(this).attr('data-ref');
    var url = "http://daerg.arts.gla.ac.uk:8080/fuseki/Faclair?output=json&query=";
    var query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \
SELECT ?h ?pos ?en \
WHERE \
{ \
  <'+ ref + '> rdfs:label ?h . \
  <'+ ref + '> a ?p . \
  ?p rdfs:label ?pos . \
  GRAPH ?g { \
    <'+ ref + '> <http://faclair.ac.uk/meta/sense> ?en . \
  } \
}';
    query = encodeURIComponent(query);
    url += query;
    $.ajax({
			  url: url
		}).done(function(data) {
			var results = data.results;
			var bindings = results.bindings;
			var output = "";
			output += "<h1>" + bindings[0].h.value + "</h1>";
			output += "<p>" + bindings[0].pos.value + "</p>";
			output += "<p>";
			$.each(bindings, function(key, value) {
			  output += value.en.value + ', ';
			});
			output += "</p>";
			$('#rhs').html(output);
		});
  });

  $('.meta').click(function(){
    var ref = $(this).attr('data-ref');
    var url = "http://daerg.arts.gla.ac.uk:8080/fuseki/corpus?output=json&query=";
    var query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \
SELECT ?p ?o ?oo \
WHERE \
{ \
    <'+ ref + '> ?n ?o . \
    ?n rdfs:label ?p . \
    OPTIONAL { ?o rdfs:label ?oo . } \
}';
    query = encodeURIComponent(query);
    url += query;
    $.ajax({
			  url: url
		}).done(function(data) {
			var results = data.results;
			var bindings = results.bindings;
			var output = "";
			$.each(bindings, function(key, value) {
			  output += '<p>This text ' + value.p.value + ' '; 
			  if (value.o.value.startsWith('http')) {
			    output += '<a href="#" class="meta" data-ref="' + value.o.value + '">' + value.oo.value + '</a>.</p>';
			  }
			  else {
			    output += '\"' + value.o.value + '\".</p>';
			  }
		  });
		  $('#rhs').html(output);
		  url = "http://daerg.arts.gla.ac.uk:8080/fuseki/corpus?output=json&query=";
		  query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \
SELECT ?s ?ss ?p \
WHERE \
{ \
    ?s ?n <'+ ref + '> . \
    ?n rdfs:label ?p . \
    ?s rdfs:label ?ss . \
}';
      query = encodeURIComponent(query);
      url += query;
		  $.ajax({
			  url: url
		  }).done(function(data) {
			  var results = data.results;
			  var bindings = results.bindings;
			  var output = "";
			  $.each(bindings, function(key, value) {
			    output += '<p><a href="#" class="meta" data-ref="' + value.s.value + '">' + value.ss.value + '</a> ' + value.p.value + ' this text.</p>';
			  });	
			  $('#rhs').append(output);
		  });
		});
  });
  
});

function showMeta(uri) {
  var url = "http://daerg.arts.gla.ac.uk:8080/fuseki/corpus?output=json&query=";
  var query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \
SELECT ?p ?o ?oo \
WHERE \
{ \
    <'+ uri + '> ?n ?o . \
    ?n rdfs:active ?p . \
    OPTIONAL { ?o rdfs:label ?oo . } \
}';
  query = encodeURIComponent(query);
  url += query;
  $.ajax({
	  url: url
	}).done(function(data) {
		var results = data.results;
		var bindings = results.bindings;
		var output = "";
		$.each(bindings, function(key, value) {
			  output += '<p>' + value.p.value; 
			  //alert(value.oo.value);
			  if (value.o.value.startsWith('http')) {
			    output += '<a href="#" onclick="showMeta(\'' + value.o.value + '\');">' + value.oo.value + '</a></p>';
			  }
			  else {
			    output += value.o.value + '</p>';
			  }
		});
		$('#rhs').html(output);
		url = "http://daerg.arts.gla.ac.uk:8080/fuseki/corpus?output=json&query=";
		query = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> \
SELECT ?s ?ss ?p \
WHERE \
{ \
    ?s ?n <'+ uri + '> . \
    ?n rdfs:passive ?p . \
    ?s rdfs:label ?ss . \
}';
    query = encodeURIComponent(query);
    url += query;
		$.ajax({
		  url: url
		}).done(function(data) {
			var results = data.results;
			var bindings = results.bindings;
			var output = "";
			$.each(bindings, function(key, value) {
			  output += '<p>' + value.p.value + '<a href="#" onclick="showMeta(\'' + value.s.value + '\');">' + value.ss.value + '</a></p>';
			});	
			$('#rhs').append(output);
		});
	});
  return true;
}

