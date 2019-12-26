;(function (w, d) {
  w.addEventListener("DOMContentLoaded", function(){
    const sendCommentBtn = d.getElementById('sendCommentBtn');
    sendCommentBtn.addEventListener('click', function(e) {
      e.preventDefault();
      submitCommentHandler();
    });

    function submitCommentHandler() {
      const form = d.querySelector('form[name=addComment]');
      const data = new FormData(form);
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "/post.php");
      xhr.send(data);
      xhr.onload = () => {
        if (xhr.response === "success") {
          const template = d.getElementById("alert-template").content.cloneNode(true); // шаблон сообщения
          const commentsContainer = d.getElementById("allCommentsCard");
          commentsContainer.insertBefore(template, commentsContainer.firstChild); // добавляем шаблон в DOM

          // Ждем пока отработает анимация и перезагружаем страницу
          setTimeout(function () {
            const alertBlock = d.querySelector('#alert-block');
            if (alertBlock) { alertBlock.remove(); }
            w.location.replace('index.php');
          }, 2000);

          // Очистить поля формы
          form.reset();

        }
      };
    }

  });
})(window, document);
