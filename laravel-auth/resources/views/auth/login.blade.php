<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>vtourism</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Rubik:wght@400;500&display=swap"
        rel="stylesheet">


    <style>
        @import url("https://fonts.googleapis.com/css?family=Rubik:700&display=swap");
        * {
            box-sizing: border-box;
        }

        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            --primary-color: mediumslateblue;
        }

        body {
            height: 100vh;
            display: grid;
            place-items: center;
            background-color: black;
            margin: 0rem;
            overflow: hidden;
            font-family: "Montserrat", sans-serif;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0rem;
        }

        .card {
            width: 640px;
            position: relative;
            background-color: rgb(16 16 16);
            border: 1px solid rgb(255 255 255 / 5%);
            border-radius: 1.5rem;
            padding: 1rem;
        }

        .card:after {
            content: "";
            height: 70px;
            width: 1px;
            position: absolute;
            left: -1px;
            top: 65%;
            transition: top, opacity;
            transition-duration: 600ms;
            transition-timing-function: ease;
            background: linear-gradient(transparent,
                    var(--primary-color),
                    transparent);
            opacity: 0;
        }

        .card:after {
            top: 65%;
            opacity: 0;
        }

        .card:hover:after {
            top: 25%;
            opacity: 1;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px,
                    transparent 1px);
            background-position: 50% 50%;
            background-size: 1.1rem 1.1rem;
            padding: 4rem;
            border-radius: 1.25rem;
            overflow: hidden;
        }

        .card-content> :is(h1, h3, p) {
            text-align: center;
        }

        .card-content>h1 {
            color: rgb(250 249 246);
            font-size: 2.6rem;
        }

        .card-content>h3 {
            color: var(--primary-color);
            text-transform: uppercase;
            font-size: 0.76rem;
        }

        .card-content>p {
            color: rgb(255 255 255 / 75%);
            line-height: 1.5rem;
        }

        @media(max-width: 700px) {
            .card {
                width: calc(100% - 2rem);
                margin: 0rem 1rem;
                padding: 0.75rem;
                border-radius: 1rem;
            }
        }

        @media(max-width: 600px) {
            .card-content {
                padding: 3rem;
            }

            .card-content>h1 {
                font-size: 2.2rem;
            }
        }

        button {
            position: relative;
            display: inline-block;
            cursor: pointer;
            outline: none;
            border: 0;
            vertical-align: middle;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
        }

        button.learn-more {
            font-weight: 600;
            color: black;
            text-transform: uppercase;
            padding: 1.25em 2em;
            background: #7b68ee;
            border: 2px solid #000000;
            border-radius: 0.75em;
            transform-style: preserve-3d;
            transition: transform 150ms cubic-bezier(0, 0, 0.58, 1), background 150ms cubic-bezier(0, 0, 0.58, 1);
        }

        button.learn-more::before {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #000000;
            border-radius: inherit;
            box-shadow: 0 0 0 2px #222222, 0 0.625em 0 0 rgb(70, 70, 70);
            transform: translate3d(0, 0.75em, -1em);
            transition: transform 150ms cubic-bezier(0, 0, 0.58, 1), box-shadow 150ms cubic-bezier(0, 0, 0.58, 1);
        }

        button.learn-more:hover {
            background: #ffe9e9;
            transform: translate(0, 0.25em);
        }

        button.learn-more:hover::before {
            box-shadow: 0 0 0 2px #b18597, 0 0.5em 0 0 #ffe3e2;
            transform: translate3d(0, 0.5em, -1em);
        }

        button.learn-more:active {
            background: white;
            transform: translate(0em, 0.75em);
        }

        button.learn-more:active::before {
            box-shadow: 0 0 0 2px white, 0 0 white;
            transform: translate3d(0, 0, -1em);
        }

        button {
            font-weight: 500;
            padding: 5px;
            border-radius: 25px;
        }

        @keyframes rotate {
            from {
                rotate: 0deg;
            }

            50% {
                scale: 1 1.5;
            }

            to {
                rotate: 360deg;
            }
        }

        #blob {
            background-color: white;
            height: 34vmax;
            aspect-ratio: 1;
            position: absolute;
            left: 50%;
            top: 50%;
            translate: -50% -50%;
            border-radius: 50%;
            background: linear-gradient(to right, aquamarine, mediumpurple);
            animation: rotate 20s infinite;
            opacity: 0.8;
        }

        #blur {
            height: 100%;
            width: 100%;
            position: absolute;
            z-index: 2;
            backdrop-filter: blur(12vmax);
        }

        .card {
            z-index: 3;
        }
    </style>

</head>

<body>
    <div id="blob"></div>
    <div id="blur"></div>
    <div class="card">
        <div class="card-content">
            <h3>vtourism</h3>
            <h1>Chào mừng đến với ứng dụng du lịch ảo VTourism!</h1>
            <p>Với ứng dụng này, giờ đây bạn có thể tạo, khám phá và tận hưởng trải nghiệm du lịch trực tuyến.</p>
            <a href="{{route("viewMap" , ['id' => 0])}}"><button class="learn-more">Bắt đầu khám phá</button></a>
            <br>
            <h3>Hoặc, tự tạo hành trình của chính bạn...</h3>
            <a href="{{route('social.redirect',['provider' => 'facebook'])}}"><button>Đăng nhập bằng Facebook</button></a>
            <a href="{{route('social.redirect',['provider' => 'google'])}}"><button>Đăng nhập bằng Google</button></a>
        </div>
    </div>
</body>

</html>