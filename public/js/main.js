window.onload = function () {
  var chatContainer =  document.getElementById('chat-history');
  if (chatContainer) {
    chatContainer.scrollTop = chatContainer.scrollHeight;
  }

  /* เพิ่ม event listener ให้กับรูปภาพ */
  var fileButton = document.getElementById('fileButton');
  if (fileButton) {
    fileButton.addEventListener('click', function() {
      document.getElementById('fileInput').click();
    });

    document.getElementById('fileInput').addEventListener('change', function() {
      document.getElementById('lineMessageForm').submit();
    });
  }

  /* เพิ่ม event listener ให้กับปุ่มส่งข้อความ */
  let quoteToken;
  let msgType;
  let msgContent;
  let userName;
  document.querySelectorAll("#actionMsg").forEach(button => {
    button.addEventListener("click", function() {
      let quoteDots = this.nextElementSibling;
      document.querySelectorAll(".actionDots").forEach(q => {
        if (q !== quoteDots) {
          q.classList.add("hidden");
        }
      });
      quoteDots.classList.toggle("hidden");
      quoteToken = this.getAttribute("data-quoteData");
      msgType = this.getAttribute("data-msgType");
      msgContent = this.getAttribute("data-msgContent");
      userName = this.getAttribute("data-userName");
    });
  });

  const quoteBox = document.getElementById('quoteBox');
  const quoteUser = document.getElementById('quoteUser');
  const quoteContent = document.getElementById('quoteContent');
  const quoteTokenInput = document.getElementById('quoteToken');
  const quoteContentInput = document.getElementById('quoteContentInput');
  const quoteTypeInput = document.getElementById('quoteTypeInput');
  const removeQuoteButton = document.getElementById('removeQuote');

  window.quoteMsg = function(button) {
    quoteUser.innerText = userName;
    quoteTokenInput.value = quoteToken;
    quoteContentInput.value = msgContent;
    quoteTypeInput.value = msgType;
    if (msgType == "image") {
      quoteContent.innerText = "รูปภาพ";
    } else if (msgType == "sticker") {
      quoteContent.innerText = "สติกเกอร์";
    } else {
      quoteContent.innerText = msgContent;
    }

    quoteBox.classList.remove('hidden');

    document.querySelectorAll(".actionDots").forEach(dot => {
      dot.classList.add("hidden");
    });
  };


  if (removeQuoteButton) {
    removeQuoteButton.addEventListener('click', function() {
      quoteBox.classList.add('hidden');
      quoteTokenInput.value = '';
      quoteContentInput.value = '';
      quoteTypeInput.value = '';
    });
 }
};