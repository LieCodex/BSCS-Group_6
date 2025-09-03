<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create Post</title>

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
                            <h1 class="h3 text-white-900">Create Post</h1>
                            
                        </div>

                        <form action="/create-post" method="POST" class="user" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input name="title" type="text" class="form-control form-control-user"
                                    placeholder="Post Title" required>
                            </div>

                            <div class="form-group">
                                <textarea name="body" class="form-control form-control-user"
                                    placeholder="Body Content" rows="4" required></textarea>
                            </div>

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
                            <div id="previewContainer" class="d-flex flex-wrap"></div>


                            <button type="submit" class="btn btn-orange btn-user btn-block btn-sm">Save Post</button>

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
