<script>
  // Function to insert <wbr> elements into long words
  function insertWBR(element) {
    const text = element.textContent;
    const words = text.split(' ');
    const breakableWords = words.map(word => {
      if (word.length > 15) {
        return word.replace(/(.{8})/g, '$1<wbr>');
      }
      return word;
    });
    element.innerHTML = breakableWords.join(' ');
  }

  // Call the function on page load or when needed
  window.addEventListener('load', function() {
    const breakWordElements = document.querySelectorAll('.break-word');
    breakWordElements.forEach(element => insertWBR(element));
  });
</script>