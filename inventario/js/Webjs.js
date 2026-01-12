$(document).ready(function() {
    $(".chosen").chosen();
});

shortcut.add("Ctrl+G", function() {
    guardarHoja008();
});

shortcut.add("Ctrl+P", function() {
    hoja008();
});

shortcut.add("Ctrl+Q", function() {
    cambiarPestanna(pestanas, pestana1);
});

shortcut.add("Ctrl+R", function() {
    cambiarPestanna(pestanas, pestana2);
});

shortcut.add("Ctrl+8", function() {
    $('html,body').animate({
        scrollTop: $("#divForm3").offset().top
    }, 1000);
});

shortcut.add("Ctrl+G", function() {
    guardarHoja();
});


shortcut.add("F8", function() {
    agregarItem();
});

var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

var states = [
'ANTISEPTICO BUCAL',
'IM',
'IM - IV',
'IM - SC',
'INHALACION ORAL',
'INHALACTORIO ORAL',
'INHALATORIA',
'INHALATORIA NASAL',
'INTRAARTICULAR',
'INTRAARTICULAR-IM',
'INTRANASAL',
'INTRAVAGINAL',
'INTRAVAGINAL - TOPICO',
'INTRAVESICAL',
'IV',
'IV - IM',
'IV - IM - SC',
'IV - SC',
'NEBULIZACION',
'OCULAR',
'ORAL-PARENTERAL-LOCAL',
'OTICO',
'RECTAL',
'SC',
'SC - IM - IV',
'SC - IV',
'SOLUCION ORAL',
'TOPICO BUCAL',
'TOPICO NASAL',
'TOPICO UNGUEAL',
'TRANSDERMICO',
'VAGINAL',
'VIA INHALATORIA',
'VIA INHALATORIA ORAL',
'VIA RECTAL',
'VIA TOPICA',
'VIA TOPICA - MUCOSA',
'VIA TÒPICA',
'VO',
'VO - RECTAL',
'VO - VR',
'VR'];

$('.administracion').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'states',
  source: substringMatcher(states)
});

var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

var states = ['CADA 2 HORAS', 
'CADA 4 HORAS',
'CADA 6 HORAS',
'CADA 8 HORAS',
'CADA 10 HORAS',
'CADA 12 HORAS',
'PRN POR RAZONES NECESARIAS',
'EN ESTE MOMENTO',
];

$('.prescripcion').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'states',
  source: substringMatcher(states)
});

var countries = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.whitespace,
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  // url points to a json file that contains an array of country names, see
  // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
  prefetch: '../hoja_005/search.php'
});

// passing in `null` for the `options` arguments will result in the default
// options being used
$('.producto').typeahead(null, {
  name: 'countries',
  source: countries
});