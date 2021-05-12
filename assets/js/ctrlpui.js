var numberDown = 0;
//var modal = document.getElementById('myModal');
var modal = null;

onkeydown = function(e) {
  if(e.ctrlKey && e.key == 'p') { // show ctrlp modal
    e.preventDefault();
    modal = document.getElementById('ctrlpModal');
    modal.style.display = "block";
    var searchBox = document.getElementById('ctrlpInput');
    searchBox.value = "";  // clear out old values when doing new search
    searchBox.focus();
    numberDown = 0;
    $("li").removeClass("activeL");
  } else if (e.key == 'Escape' || e.key == 'Esc') {  // close modals
    modal.style.display = "none";
    numberDown = 0;
  } else if (e.key == 'ArrowDown' || e.key == 'Down') {
    if (modal.style.display == "block") {
      e.preventDefault();
      $("li").removeClass("activeL");
      document.getElementById('ctrlpResultsList').getElementsByTagName("li")[numberDown].className = "activeL";
      numberDown = numberDown + 1;
      // if numberDown = list size ... go to 0
      var cool = $("#ctrlpResultsList li").length;
      if (numberDown >= cool) {
        numberDown = 0;
      }
    }
  } else if (e.key == 'ArrowUp' || e.key == 'Up') {
    // var modal = document.getElementById('ctrlpModal');
    if (modal.style.display == "block") {
      e.preventDefault();
      $("li").removeClass("activeL");
      document.getElementById('ctrlpResultsList').getElementsByTagName("li")[numberDown].className = "activeL";
      numberDown = numberDown - 1;
      var cool = $("#ctrlpResultsList li").length;
      if (numberDown <= cool) {
        numberDown = 0;
      }
    }
  } else if (e.key == 'Enter') {
    if (modal.style.display == 'block') {
      // grab the name from the highlighted option!
      var songToFind = $("li.activeL").text();
      var x = dataSets.songs;
      var findIt = x.indexOf(songToFind);
      var sourcesArray = audioData.audiosources;
      var found = sourcesArray[findIt];
    }
  }
}

// had script seperators right here between } and var patternField

var patternField;
var matchFn = fuzzy_match;
var resultsList = null;
var currentDataSet = dataSets["songs"];

var asyncMatcher = null;

onload = function() {
    // Initialize document element references
    patternField = document.getElementById('ctrlpInput');
    patternField.oninput = onPatternChange;
    patternField.onpropertychange = patternField.oninput;

    resultsList = document.getElementById('ctrlpResultsList');
};

displayResults = function(results) {
    var newResultsList = resultsList.cloneNode(false);

    // Because adding too many elements is catastrophically slow because HTML is slow
    //var max_entries = 500;  // slim down max entries a bit - user shouldn't have that many songs
    var max_entries = 20;

    // Create HTML elements for results
    for (index = 0; index < results.length && index < max_entries; ++index) {
        var li = document.createElement('li');
        li.innerHTML = results[index];
        newResultsList.appendChild(li);
    }

    // Replace the old results from the DOM.
    resultsList.parentNode.replaceChild(newResultsList, resultsList);
    resultsList = newResultsList;
};

onPatternChange = function() {
    numberDown = 0; // allow for further arrow key selection
    // Clear existing async match if it exists
    if (asyncMatcher !== null) {
        asyncMatcher.cancel();
        asyncMatcher = null;
    }

    var pattern = patternField.value;

    // Data not yet loaded
    if (currentDataSet == null)
        return;

    if (resultsList !== null)
    {
        // Clear the list
        var emptyList = resultsList.cloneNode(false);
        resultsList.parentNode.replaceChild(emptyList, resultsList);
        resultsList = emptyList;
    }

    // Early out on empty pattern (such as startup) because JS is slow
    if (pattern.length == 0)
        return;

    asyncMatcher = new fts_fuzzy_match_async(matchFn, pattern, currentDataSet, function(results) {
        // Scored function requires sorting
        if (matchFn == fuzzy_match) {
            results = results
                .sort(function(a,b) { return b[1] - a[1]; })
                .map(function(v) { return v[2]; });
        }

        displayResults(results);

        asyncMatcher = null;
    });
    asyncMatcher.start();
};

onDataSetChange = function(radio) {
    var setname = radio.value;
    currentDataSet = dataSets[setname];
    onPatternChange();
};