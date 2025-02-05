let page = 1;

document.getElementById('loadMore').addEventListener('click', function () {
    page++;
    loadMorePosts(page);
});

function loadMorePosts(page) {
    fetch(`/user/posts/load?page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.data.length > 0) {
                data.data.forEach(post => {
                    let postContainer = document.createElement('div');
                    postContainer.className = 'card mb-3';
                    postContainer.innerHTML = `
                    <div class="card-body">
                        <h6>${post.user.name}</h6>
                        <p class="post-text">${post.text}</p>
                        ${post.images.length ? '<div class="post-images-container">' + post.images.map(image => `
                            <a href="/storage/${image.path}" data-lightbox="post">
                                <img src="/storage/${image.path}" class="post-image">
                            </a>`).join('') + '</div>' : ''}
                        <p><small class="text-muted">${post.created_at.diffForHumans()}</small></p>
                        <button class="btn btn-sm btn-warning edit-post-btn" data-post-id="${post.id}">Редактировать</button>
                    </div>
                `;
                    document.getElementById('postsContainer').appendChild(postContainer);
                });
            } else {
                document.getElementById('loadMore').style.display = 'none'; // Скрываем кнопку, если больше нет постов
            }
        });
}
