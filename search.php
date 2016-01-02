<script type="text/javascript" src="jquery/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui.min.css" />
<script type="text/javascript">
$(function() {
  var availableTags = [
      "Programming",
      "Guitar",
      "Piano",
      "Painting",
      "Cooking",
      "Dance",
      "Self Defence",
      "Parkour",
      "Soccer",
      "Basketball",
      "Hockey",
      "Football",
      "Rugby",
      "Drum",
      "Violin",
      "Flute",
      "Trumpet",
      "Volleyball",
      "Skiing",
      "Drawing",
      "Communication",
      "Math",
      "Science",
      "Photoshop",
      "Photography",
      "Illustrator",
      "App development",
      "Android app development",
      "Apple app development",
      "Game development",
      "Art",
      "CAD",
      "Excel",
      "Drafting",
      "Meditation",
      "Leadership",
      "Business Management",
      "Marketing",
      "Accounting",
      "Mortgage",
      "Loan",
      "Textiles",
      "Video editing",
      "After Effects",
      "Physics",
      "Chemistery",
      "Biology",
      "Tennis",
      "Skating",
      "Ice Skating",
      "Driving",
      "Psychology",
      "History",
      "Spanish",
      "French",
      "German",
      "Punjabi",
      "Japanese",
      "Korean",
      "Mandarin",
      "Portuguese",
      "Presentation",
      "Geography",
      "YouTube",
      "Facebook",
      "Twitter",
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "CSS",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "jQuery",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme",
    ];
    $( ".searchTermMain, #setting-interest, #searchnav" ).autocomplete({
      source: function(request, response) {
          var results = $.ui.autocomplete.filter(availableTags, request.term);
          response(results.slice(0, 10));
      }
    });

    $('.searchMainButton').click(function() {
      var search = $(this).siblings('.searchTermMain').val();
      var searchUrl = search.split('+').join('OplusO');
      var searchUrl2 = searchUrl.split(' ').join('+');

      if(search != "") {
        window.location = "searchResult.php?s="+searchUrl2;
      }
    });

})
</script>
