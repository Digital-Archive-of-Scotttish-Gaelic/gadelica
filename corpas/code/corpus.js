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
    alert(url);
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

});



