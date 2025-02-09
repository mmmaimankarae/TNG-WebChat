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
            console.log('change');
            document.getElementById('lineMessageForm').submit();
        });
    }
};