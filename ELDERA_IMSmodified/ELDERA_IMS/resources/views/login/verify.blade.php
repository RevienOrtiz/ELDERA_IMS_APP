<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <title>ELDERA - Email Verification</title>
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
        font-size: clamp(1.2rem, 3.5vw, 1.8rem);
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }

    .verification-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.2rem;
        min-width: clamp(280px, 80vw, 400px);
    }

    .form-label {
        font-size: clamp(1.0rem, 2.0vw, 1rem);
        font-weight: 600;
        color: #111;
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
        justify-content: center;
    }

    .form-input {
        padding: 15px 20px;
        width: clamp(200px, 50vw, 300px);
        border-radius: 6px;
        border: 2px solid #e31575;
        background-color: white;
        outline: none;
        font-size: clamp(1.2rem, 3vw, 1.5rem);
        text-align: center;
        letter-spacing: 0.5rem;
        font-weight: bold;
    }

    .form-input:focus {
        border-color: #c4125f;
        box-shadow: 0 0 10px rgba(227, 21, 117, 0.3);
    }

    .button {
        padding: clamp(0.8rem, 2vw, 1rem) clamp(1.5rem, 3vw, 2rem);
        border: none;
        border-radius: 20px;
        font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        font-weight: 500;
        cursor: pointer;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        width: clamp(200px, 40vw, 300px);
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

    .code-display {
        background-color: #f8f9fa;
        border: 2px dashed #e31575;
        border-radius: 10px;
        padding: 1rem;
        margin: 1rem 0;
        font-family: monospace;
        font-size: 1.2rem;
        font-weight: bold;
        color: #e31575;
    }

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
    </style>
</head>

<body>
    <img src="{{asset('images/LCSCF_LOGO.png')}}" alt="LCSCF Logo" class="logo">
    <main>
        <div class="container">
            <div class="form-container">
                <div class="form-title">EMAIL VERIFICATION</div>
                <div class="form-subtitle">Enter the verification code sent to {{ $user->email }}</div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success" style="color: green; margin-bottom: 1rem; padding: 0.5rem; background-color: #e6ffe6; border: 1px solid green; border-radius: 4px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($verificationCode)
                <div class="code-display">
                    <strong>For Testing:</strong><br>
                    Your verification code is: {{ $verificationCode->code }}
                </div>
            @endif
            
            <form action="{{ route('verify.post') }}" method="POST" class="verification-form">
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger" style="color: red; margin-bottom: 1rem; padding: 0.5rem; background-color: #ffe6e6; border: 1px solid red; border-radius: 4px;">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <div class="mb-3">
                    <label class="form-label">VERIFICATION CODE:</label>
                    <input type="text" class="form-input" name="code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem; width: 100%; align-items: center;">
                    <button type="submit" class="button verify-button">VERIFY & LOGIN</button>
                    
                    <form action="{{ route('resend.code') }}" method="POST" style="width: 100%;">
                        @csrf
                        <button type="submit" class="button resend-button">RESEND CODE</button>
                    </form>
                </div>
            </form>
        </div>
    </main>

    <div id="circle1"></div>
    <div id="circle2"></div>
    <div><img src="{{asset('images/Bagong_Pilipinas.png')}}" alt="Bagong Pilipinas" class="footer-logo"></div>

    <script>
        // Auto-focus on code input
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.querySelector('input[name="code"]');
            if (codeInput) {
                codeInput.focus();
            }
        });

        // Auto-submit when 6 digits are entered
        document.querySelector('input[name="code"]').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                e.target.form.submit();
            }
        });
    </script>
</body>
</html>
