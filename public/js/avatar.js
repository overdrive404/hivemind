document.querySelector('input[type="file"]').addEventListener('change', function(event) {
    const files = event.target.files;
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';  // Очистить старые превью

    Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgElement = document.createElement('img');
            imgElement.src = e.target.result;
            imgElement.classList.add('img-thumbnail', 'mr-2');
            imgElement.style.maxWidth = '100px';
            preview.appendChild(imgElement);
        };
        reader.readAsDataURL(file);
    });
});

let selectedFiles = [];
document.getElementById('images').addEventListener('change', function(event) {
    let preview = document.getElementById('preview');
    let newFiles = Array.from(event.target.files);

    if (selectedFiles.length + newFiles.length > 10) {
        alert('Можно загрузить не более 10 изображений.');
        return;
    }

    newFiles.forEach(file => {
        selectedFiles.push(file);
        let reader = new FileReader();
        reader.onload = function(e) {
            let imgContainer = document.createElement('div');
            imgContainer.classList.add('position-relative');


            let img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('img-thumbnail');
            img.style.maxWidth = '100px';
            img.style.maxHeight = '100px';

            let removeBtn = document.createElement('button');
            removeBtn.innerHTML = '&times;';
            removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'position-absolute', 'top-0', 'end-0');
            removeBtn.onclick = function() {
                selectedFiles = selectedFiles.filter(f => f !== file);
                let newFileList = new DataTransfer();
                selectedFiles.forEach(f => newFileList.items.add(f));
                event.target.files = newFileList.files;
                imgContainer.remove();

            };

            imgContainer.appendChild(img);
            imgContainer.appendChild(removeBtn);
            preview.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });

    let newFileList = new DataTransfer();
    selectedFiles.forEach(f => newFileList.items.add(f));
    event.target.files = newFileList.files;
});


///////////////////
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-post-btn").forEach(button => {
        button.addEventListener("click", function () {
            let postCard = this.closest(".card");
            let postId = this.dataset.postId;
            let postTextElem = postCard.querySelector(".post-text");

            if (!postTextElem) {
                console.error("Элемент .post-text не найден в посте");
                return;
            }

            let postText = postTextElem.innerText;

            // Проверка, нет ли уже формы редактирования
            if (postCard.querySelector(".edit-form")) {
                return;
            }

            // Создаём контейнер с формой редактирования
            let editForm = document.createElement("div");
            editForm.classList.add("edit-form", "mt-3"); // Добавляем отступ сверху

            editForm.innerHTML = `
                <textarea class="form-control" id="edit-text-${postId}">${postText}</textarea>
                <input type="file" id="new-images-${postId}" multiple class="form-control mt-2">
                <div id="preview-new-images-${postId}" class="mt-2"></div>
                <div class="mt-2">
                    <button type="button" class="btn btn-primary save-edit-btn" data-post-id="${postId}">Сохранить</button>
                    <button class="btn btn-secondary cancel-edit-btn" data-post-id="${postId}">Отмена</button>
                </div>
            `;

            // Вставляем форму в конец карточки поста
            postCard.querySelector(".card-body").appendChild(editForm);


            editForm.querySelector(".cancel-edit-btn").addEventListener("click", function () {
                editForm.remove(); // Удаление формы
            });
        });
    });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("save-edit-btn")) {
            event.preventDefault();
            let postId = event.target.dataset.postId;
            let newText = document.getElementById(`edit-text-${postId}`).value;
            let imagesInput = document.getElementById(`new-images-${postId}`);
            let images = imagesInput.files;

            let formData = new FormData();
            formData.append("text", newText);
            formData.append("_method", "PUT");
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').content);

            for (let i = 0; i < images.length; i++) {
                formData.append("images[]", images[i]);
            }

            fetch(`/user/posts/${postId}`, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Ошибка при обновлении поста");
                    }
                });
        }
    });
});
