<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <title>ELDERA - Main</title>
    
   <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background-color: rgb(42, 44, 41);
        min-height: 100vh;
        overflow-x: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .logo {
        position: absolute;
        top: 2rem;
        left: 2rem;
        width: clamp(60px, 8vw, 100px);
        height: clamp(60px, 8vw, 100px);
        max-width: 15vw;
        border: 2px solid #e31575;
        border-radius: 50%;
        background-color: #e31575;
        object-fit: cover;
    }

    h1 {
        color: #e31575;
        font-size: clamp(1.4rem, 4.5vw, 3.5rem);
        font-weight: 900;
        margin-top: 0;
        line-height: 1.2;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        text-align: left;
    }

    h2 {
        color: #2A2C29;
        font-size: clamp(1.2rem, 3.5vw, 2.5rem);
        margin-bottom: 0;
        font-weight: 800;
        text-align: left;
    }

    main {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        min-height: calc(100vh - 120px);
        padding: clamp(0.5rem, 2vw, 1rem);
        padding-left: clamp(0.5rem, 2vw, 2rem);
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        max-width: 1000px;
        padding: 0 clamp(1rem, 3vw, 1rem);
        margin-top: 5rem;
    }

    .button-group {
        margin-top: clamp(2rem, 4vw, 1rem);
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: clamp(0.5rem, 2vw, 1rem);
    }

    .button {
        padding: clamp(0.8rem, 2vw, 1rem) clamp(1.5rem, 3vw, 2rem);
        border: none;
        border-radius: 20px;
        font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        font-weight: 500;
        cursor: pointer;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        width: clamp(150px, 25vw, 200px);
        height: clamp(40px, 8vw, 50px);
        transition: transform 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
    }

    .button:hover {
        transform: translateY(-2px);
    }

    .login-button {
        background-color: #e31575;
        color: white;
        font-weight: 700;
    }

    .signup-button {
        background-color: #333;
        color: white;
        font-weight: 700;
    }

    .footer-logo {
        position: absolute;
        bottom: 2rem;
        right: 2rem;
        width: clamp(60px, 8vw, 100px);
        max-width: 15vw;
        height: auto;
        filter: drop-shadow(0 0 0 white) drop-shadow(0 0 2px white);
    }

    .menu-icon {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        font-size: 1.5rem;
        color: rgb(0, 0, 0);
        z-index: 4;
        cursor: pointer;
    }

    #circle1 {
        height: 100vh;
        width: 200vw;
        position: absolute;
        background: #FFB7CE;
        border-radius: 50%;
        bottom: 10%;
        left: -70%;
        border: 8px solid #e31575;
        z-index: -2;
    }

    #circle2 {
        height: 120vh;
        width: 200vw;
        position: absolute;
        background: white;
        border-radius: 50%;
        bottom: 14%;
        left: -72%;
        z-index: -1;
    }

    /* Media Queries */
    @media (max-width: 768px) {
        .logo {
            width: clamp(50px, 10vw, 80px);
            height: clamp(50px, 10vw, 80px);
            top: 0.5rem;
            left: 0.5rem;
        }

        main {
            justify-content: center;
            padding: 1rem;
        }

        .container {
            align-items: center;
            padding: 0 1rem;
        }

        h1, h2 {
            text-align: center;
        }

        .button-group {
            flex-direction: column;
            align-items: center;
            width: 100%;
            justify-content: center;
        }

        .button {
            width: clamp(180px, 80vw, 250px);
            height: 45px;
        }
    }

    @media (max-width: 480px) {
        .logo {
            width: clamp(40px, 12vw, 60px);
            height: clamp(40px, 12vw, 60px);
        }

        .footer-logo {
            width: clamp(50px, 12vw, 80px);
            bottom: 0.5rem;
            right: 1rem;
        }

        .button {
            width: clamp(160px, 85vw, 200px);
            height: 40px;
            font-size: clamp(0.8rem, 3vw, 1rem);
        }

        h1 {
            font-size: clamp(1.2rem, 5vw, 2.5rem);
        }

        h2 {
            font-size: clamp(1rem, 4vw, 1.8rem);
        }
    }

    @media (max-width: 320px) {
        .logo {
            width: 35px;
            height: 35px;
        }

        .button {
            width: 140px;
            height: 35px;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .container {
            padding: 0 0.5rem;
        }
    }
</style>

</head>

<body>
    <img src="{{asset('images/LCSCF_LOGO.png')}}" alt="LCSCF Logo" class="logo">
    
    <main>
        <div class="container">
            <h2>WELCOME TO</h2>
            <h1>LINGAYEN CAPITAL SENIOR<br>CITIZENS FEDERATION</h1>

            <div class="button-group">  
                <a href="{{ route('login') }}" style="text-decoration: none;">
                    <button class="button login-button">LOG IN</button>
                </a>

                {{-- <a href="{{ route('signup') }}" style="text-decoration: none;">
                    <button class="button signup-button">SIGN UP</button>
                </a> --}}
            </div>
        </div>
    </main>

    <div id="circle1"></div>
    <div id="circle2"></div>
    <div><img src="{{asset('images/Bagong_Pilipinas.png')}}" alt="Bagong Pilipinas" class="footer-logo"></div>
</body>
</html>
