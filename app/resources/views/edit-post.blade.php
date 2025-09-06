<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Post</title>

    <!-- Bootstrap CSS -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/css/sb-admin-2.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="h3 text-white-900">Edit Post</h1>
                        </div>

                        <form action="/edit-post/{{$post->id}}" method="POST" class="user" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="form-group">
                                <input type="text" name="title" value="{{$post->title}}"
                                    class="form-control form-control-user" placeholder="Post Title" required>
                            </div>

                            <!-- Body -->
                            <div class="form-group">
                                <textarea name="body" class="form-control form-control-user" rows="4"
                                    placeholder="Body Content" required>{{$post->body}}</textarea>
                            </div>

                            <!-- Show existing images -->
                            <div class="form-group d-flex flex-wrap">
                                @foreach($post->images as $image)
                                    <div class="position-relative m-2">
                                        <img src="{{ $image->image_path }}" 
                                            class="rounded" 
                                            style="width:100px; height:100px; object-fit:cover;">
                                            
                                    </div>
                                @endforeach 
                            </div>

                            <!-- Upload new images -->
                            <div class="form-group">
                                <label for="imageInput" class="custom-file-upload">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Images
                                </label>
                                <input 
                                    type="file" 
                                    name="images[]" 
                                    id="imageInput" 
                                    multiple 
                                    accept="image/*"
                                >
                            </div>

                            <!-- Preview Container -->
                            <div id="editPreviewContainer" class="d-flex flex-wrap"></div>

                            <!-- Save Button -->
                            <button type="submit" class="btn btn-orange btn-user btn-block btn-sm">Save Changes</button>

                            <!-- Back Button -->
                            <a href="/" class="btn btn-orange btn-user btn-block btn-sm">Cancel</a>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Multiple Image Preview JS -->
    <script src="{{ asset('assets/js/image_preview.js') }}"></script>

</body>
</html>
