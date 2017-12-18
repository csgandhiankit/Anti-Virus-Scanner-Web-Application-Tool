<html><head>
    <style>
        
    </style>
    </head>
<body>
    <input type="file" id="file-input">
<h3>Contents of the file:</h3>
<pre id="file-content"></pre>

    <script type="text/javascript">
    	  var file;
    	  var lineno = 1;

        function readSingleFile(e) {
   file = e.target.files[0];
  if (!file) {
    return;
  }
  var reader = new FileReader();
  reader.onload = function(e) {
    var contents = e.target.result;
    displayContents(contents);
  };
  reader.readAsText(file);
}

function displayContents(contents) {
  var element = document.getElementById('file-content');
  element.textContent = contents;


  readSomeLines(file, 1, function(line) {
        // console.log("Line: " + (lineno++) + line);
        if(line.substring(0,20) == "axgrtydufhrtsyhfbdlh")
        {
        	console.log(line.substring(0,20));
        	console.log("matched!!")
        }
        else
        {
        	console.log(line.substring(0,20));
        	console.log("Opps!!")
        }

        console.log(line.substring(0,20));
    }, function onComplete() {
        console.log('Read all lines');
    });
}

document.getElementById('file-input')
  .addEventListener('change', readSingleFile, false);

  function readSomeLines(file, maxlines, forEachLine, onComplete) {
    var CHUNK_SIZE = 50000; // 50kb, arbitrarily chosen.
    var decoder = new TextDecoder();
    var offset = 0;
    var linecount = 0;
    var linenumber = 0;
    var results = '';
    var fr = new FileReader();
    fr.onload = function() {
        // Use stream:true in case we cut the file
        // in the middle of a multi-byte character
        results += decoder.decode(fr.result, {stream: true});
        var lines = results.split('\n');
        results = lines.pop(); // In case the line did not end yet.
        linecount += lines.length;
    
        if (linecount > maxlines) {
            // Read too many lines? Truncate the results.
            lines.length -= linecount - maxlines;
            linecount = maxlines;
        }
    
        for (var i = 0; i < lines.length; ++i) {
            forEachLine(lines[i] + '\n');
        }
        offset += CHUNK_SIZE;
        seek();
    };
    fr.onerror = function() {
        onComplete(fr.error);
    };
    seek();
    
    function seek() {
        if (linecount === maxlines) {
            // We found enough lines.
            onComplete(); // Done.
            return;
        }
        if (offset !== 0 && offset >= file.size) {
            // We did not find all lines, but there are no more lines.
            forEachLine(results); // This is from lines.pop(), before.
            onComplete(); // Done
            return;
        }
        var slice = file.slice(offset, offset + CHUNK_SIZE);
        fr.readAsArrayBuffer(slice);
    }
}
    </script>

</body></html>