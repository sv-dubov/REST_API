<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test API</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row mt-3 posts">
    </div>
    <div class="row mt-3 full-post d-none">
        <div class="card">
            <div class="card-header">
                Full Post
            </div>
            <div class="card-body">
                <h5 class="card-title post-title"></h5>
                <p class="card-text post-content"></p>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-4">
        <form action="">
            <div class="mb-3">
                <label for="title" class="form-label">Post title</label>
                <input type="text" class="form-control" id="title">
                <div class="alert alert-danger mt-2 d-none" id="title-error" role="alert">
                </div>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Post content</label>
                <textarea class="form-control" id="content" rows="3"></textarea>
                <div class="alert alert-danger mt-2 d-none" id="content-error" role="alert">
                </div>
            </div>
            <button type="button" class="btn btn-success" onclick="store()">Add</button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
<script>
    $.ajax({
        url: "/api/posts",
        type: "GET",
        dataType: "json",
        success(data) {
            for (let index in data) {
                $('.posts').append(`
                    <div class="card" style="width: 18rem; margin-right: 10px;">
                        <div class="card-body">
                            <h5 class="card-title">${data[index].title}</h5>
                            <p class="card-text">${data[index].content.slice(0, 20)}...</p>
                            <button type="button" class="btn btn-primary" onclick="fullPost(${data[index].id})">Show</button>
                        </div>
                    </div>
                `)
            }
        }
    })

    function fullPost(id) {
        $.ajax({
            url: "/api/posts/" + id,
            type: "GET",
            dataType: "json",
            success(data) {
                $('.post-title').text(data.title);
                $('.post-content').text(data.content);
                $('.full-post').removeClass('d-none');
            }
        })
    }

    function store() {
        const title = $('#title'),
            content = $('#content');

        $('#title-error').addClass('d-none');
        $('#content-error').addClass('d-none');

        $.ajax({
            url: "/api/posts",
            type: "POST",
            dataType: "json",
            data: {
                title: title.val(),
                content: content.val()
            },
            error(err) {
                const data = err.responseJSON;
                for (let key in err.responseJSON.errors) {
                    let error_text = err.responseJSON.errors[key][0];
                    $(`#${key}-error`).removeClass('d-none').text(error_text);
                }
            },
            success(data) {
                title.val('');
                content.val('');

                $('.posts').append(`
                    <div class="card" style="width: 18rem; margin-right: 10px; margin-bottom: 10px;">
                        <div class="card-body">
                            <h5 class="card-title">${data.post.title}</h5>
                            <p class="card-text">${data.post.content.slice(0, 20)}...</p>
                            <a href="#" class="btn btn-primary" onclick="fullPost(${data.post.id})">Show</a>
                        </div>
                    </div>
                `);
                console.log(data)
            }
        })
    }
</script>
</body>
</html>
