<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <title>ELDERA - Authentication</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
        margin: 0;
        font-family: "Poppins", sans-serif;
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

    /* Layout */
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 120px);
        text-align: center;
        padding: clamp(0.5rem, 2vw, 1rem);
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 clamp(1rem, 3vw, 2rem);
    }

    /* Form Styles */
    .form-container {
        margin-bottom: 2rem;
    }

    .form-title {
        font-size: clamp(1.4rem, 4.5vw, 2.5rem);
        font-weight: 900;
        color: #e31575;
    }

    .form-subtitle {
        font-size: clamp(1.4rem, 4.5vw, 2.3rem);
        font-weight: 800;
        color: #333;
    }

    .authentication-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 1.2rem;
        min-width: clamp(280px, 80vw, 320px);
    }

    .auth-message {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            color: #333;
            font-weight: 600;
           
        }

        .auth-description {
            font-size: clamp(0.9rem, 2vw, 1rem);
            color: #333;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.2rem;
        }

        .code-inputs {
            display: flex;
            gap: 12px;
            margin-bottom: 0.5rem;
        }

        .code-input {
            width: 48px;
            height: 48px;
            font-size: 16px;
            text-align: center;
            border: 2px solid #e31575;
            border-radius: 6px;
            outline: none;
        }

        .timer {
            font-size: 18px;
            color: #222;
            
        }
    /* Responsive Button Styles - REPLACE the existing button styles */
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

    .verify-button {
        background-color: #e31575;
        color: white;
        font-weight: 700;
    }

    .resend-button {
        background-color: #333;
        color: white;
        font-weight: 700;
    }

    /* Footer */
    .footer-logo {
        position: absolute;
        bottom: clamp(0.5rem, 2vw, 2rem);
        right: clamp(1rem, 3vw, 2rem);
        width: clamp(60px, 8vw, 100px);
        max-width: 15vw;
        height: auto;
        filter: drop-shadow(0 0 0 white) drop-shadow(0 0 2px white);
        z-index: 1;
    }


        /* Header Styles */
        .header_container {
            z-index: 2;
        }
        
        .header {
            padding: 2rem;
            text-align: center;
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

        /* Background Elements */
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

    .verification-link {
        background-color: #f8f9fa;
        border: 2px dashed #e31575;
        border-radius: 10px;
        padding: 1rem;
        margin: 1rem 0;
        font-family: monospace;
        font-size: 0.9rem;
        word-break: break-all;
        color: #e31575;
    }

    </style>
</head>

<body>
     <img src="{{asset('images/LCSCF_LOGO.png')}}" alt="LCSCF Logo" class="logo">
    <main>
        <div class="container">
            <div class="form-container">
                <div class="form-title">SENIOR CITIZEN</div>
                <div class="form-subtitle">INFORMATION MANAGEMENT SYSTEM</div>
            </div>
            <div class="auth-message">WE NEED TO VERIFY YOUR EMAIL</div>
            <div class="auth-description">Please check your email for a verification link, or click the button below to verify manually.</div>
            
            @if(session('message'))
                <div class="alert alert-success" style="color: green; margin-bottom: 1rem; padding: 0.5rem; background-color: #e6ffe6; border: 1px solid green; border-radius: 4px;">
                    {{ session('message') }}
                </div>
            @endif

            @if(isset($verificationUrl))
                <div style="margin: 2rem 0;">
                    <a href="{{ $verificationUrl }}" class="button verify-button" style="text-decoration: none; display: inline-block;">
                        CLICK HERE TO VERIFY EMAIL
                    </a>
                </div>
                
                <div class="verification-link">
                    <strong>Or copy this link:</strong><br>
                    {{ $verificationUrl }}
                </div>
            @endif

            <div class="auth-form">
                <div class="button-group" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">
                    <form action="{{ route('verification.send') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="button resend-button">RESEND EMAIL</button>
                    </form>
                    
                    <a href="{{ route('logout') }}" style="text-decoration: none;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <button type="button" class="button verify-button">LOGOUT</button>
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div id="circle1"></div>
    <div id="circle2"></div>
    <div><img src="{{asset('images/Bagong_Pilipinas.png')}}" alt="Bagong Pilipinas" class="footer-logo"></div>
</body>
</html>
