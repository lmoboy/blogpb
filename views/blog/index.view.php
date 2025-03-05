<!DOCTYPE html>
<html lang="lv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi ieraksti</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 70%;
            max-width: 600px;
            font-size: 16px;
        }

        ul {
            list-style: none;
            padding: 0;
            max-width: 800px;
            margin: 0 auto;
        }

        li {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        li:nth-child(even) {
            background-color: #f9f9f9;
        }

        .post-options {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .options-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #777;
            padding: 5px;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            padding: 10px 0;
            display: none;
            z-index: 10; 
        }

        .dropdown-menu.show {
            display: block; 
        }


        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 10px 15px;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            color: #333;
        }

        .dropdown-menu button:hover {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h1>Visi bloga ieraksti</h1>

    <div class="search-container">
        <input type="text" class="search-input" placeholder="Meklēt ierakstus...">
    </div>

    <div class="create-post-container" style="text-align: center; margin-bottom: 20px;">
        <form id="create-post-form" onsubmit="createPost(event)" style="display: flex; max-width: 800px; margin: 0 auto; gap: 10px;">
            <input type="text" id="post-content" class="search-input" style="margin: 0;" placeholder="Rakstīt jaunu ierakstu..." required>
            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Publicēt</button>
        </form>
    </div>

    <ul>
        <?php foreach ($posts as $post) : ?>
            <li data-post-id="<?= $post['id'] ?>">
                <?= $post['content'] ?>
                <div class="post-options">
                    <button class="options-button" onclick="toggleDropdown(this)">...</button>
                    <div class="dropdown-menu">
                        <button onclick="editPost(this)">Rediģēt</button>
                        <button onclick="deletePost(this)">Dzēst</button>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <script>
        function toggleDropdown(button) {
            const dropdownMenu = button.nextElementSibling;
            dropdownMenu.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.options-button')) {
                const dropdowns = document.getElementsByClassName("dropdown-menu");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        function editPost(button) {
            const postElement = button.closest('li');
            const currentContent = postElement.firstChild.textContent.trim();
            const postId = postElement.dataset.postId;

            const newContent = prompt('Edit post:', currentContent);
            if (newContent !== null && newContent !== currentContent) {
                fetch(`/blog/sprinkle?id=${postId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ content: newContent })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            return text;
                        }
                    });
                })
                .then(() => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update post. Please try again.');
                });
            }
        }

        function deletePost(button) {
            if (confirm('Are you sure you want to delete this post?')) {
                const postElement = button.closest('li');
                const postId = postElement.dataset.postId;

                fetch(`/blog/yeet?id=${postId}`, {
                    method: 'POST'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            return text;
                        }
                    });
                })
                .then(() => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete post. Please try again.');
                });
            }
        }
        function createPost(event) {
            event.preventDefault();
            const content = document.getElementById('post-content').value;

            fetch('/blog/bake', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ content: content })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        return text;
                    }
                });
            })
            .then(() => {
                document.getElementById('post-content').value = '';
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to create post. Please try again.');
            });
        }
    </script>
</body>

</html>