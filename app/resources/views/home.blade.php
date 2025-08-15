<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @auth
    <p>Welcome</P>
    <form action="/logout" method="GET">
        @csrf
        <button>Logout</button>
    </form>
    <div style = "border: 3px solid black;">
        <h2>Create a new post</h2>

        <form action="/create-post" method="GET">
            @csrf
            <input name="title" type="text" placeholder="post-title">
            <textarea name="body" placeholder="body-content"></textarea>
            <button>Save Post</button>
        </form>

    @else
    <div style = "border: 3px solid black;">
        <h2>Register</h2>
        <form action="/register" method="GET">  <!-- error when using POST just change it to GET-->
            @csrf
            <input name="name" type="text" placeholder="name">
            <input name="email" type="text" placeholder="email">
            <input name="password" type="password" placeholder="password">
            <button>Register</button>
        </form>
    </div>
    <div style = "border: 3px solid black;">
        <h2>Login</h2>
        <form action="/login" method="GET">  <!-- error when using POST just change it to GET-->
            @csrf
            <input name="loginname" type="text" placeholder="name">
            <input name="loginpassword" type="password" placeholder="password">
            <button>Login</button>
        </form>
    </div>
    @endauth
    
</body>
</html> 