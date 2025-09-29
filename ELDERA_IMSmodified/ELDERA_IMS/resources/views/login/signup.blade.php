<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <title>ELDERA - Sign Up</title>
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

        .header_container {
            z-index: 2;
        }
        
        .header {
            padding: 2rem;
            text-align: center;
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

        .menu-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.5rem;
            color: rgb(0, 0, 0);
            z-index: 4;
            cursor: pointer;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px);
            text-align: center;
            padding: 1rem;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

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

    .signup-form {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 2rem;
        min-width: clamp(600px, 80vw, 800px);
        justify-content: space-between;
    }

    .form-column {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        flex: 1;
    }

    @media (max-width: 768px) {
        .signup-form {
            flex-direction: column;
            min-width: clamp(280px, 80vw, 320px);
            gap: 1.2rem;
        }
    }

        .form-label {
        font-size: clamp(1.0rem, 2.0vw, 1rem);
        font-weight: 600;
        color: #111;
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
    }

    .form-input {
        flex: 1;
        padding: 10px 10px;
        width: clamp(280px, 60vw, 380px);
        border-radius: 6px;
        border: 1px solid #e31575;
        background-color:white;
        outline: none;
        font-size: clamp(1rem, 2vw, 1rem);
        margin-left: 1.5rem;
    }

        .password-requirements {
            font-size: 0.9rem;
            color: #333;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .requirement-title {
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: #333;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .requirement-item.valid {
            color: #28a745;
        }

        .check-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            font-size: 0.8rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .requirement-item.valid .check-icon {
            background-color: #28a745;
        }

    .valid {
        color: green;
        font-weight: bold;
    }

    .invalid {
        color: red;
    }

    .form-actions {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        }

        .login-link {
            font-size: 1rem;
            color: #222;
        }

        .login-link a {
            color: #1a237e;
            text-decoration: none;
        }

        .button-group {
            margin-top: 3rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
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

        .signup-button {
            background-color: #333;
            color: white;
        }

        .footer-logo {
            position: absolute;
            bottom: 1rem;
            right: 2rem;
            width: 100px;
            max-width: 20vw;
            height: auto;
            filter: drop-shadow(0 0 0 white) drop-shadow(0 0 2px white); /* simulate white border */
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
      <form class="signup-form">
        <div class="form-column">
          <div class="mb-3">
            <label class="form-label">USERNAME:</label>
            <div class="input-group">
              <span class="input-group-text" style="background-color: #e31575; color: white;"><i class="fas fa-user"></i></span>
              <input type="text" class="form-input" name="username" placeholder="USERNAME" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">EMAIL:</label>
            <div class="input-group">
              <span class="input-group-text" style="background-color: #e31575; color: white;"><i class="fas fa-envelope"></i></span>
              <input type="email" class="form-input" name="email" placeholder="EMAIL" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">ID NO:</label>
            <div class="input-group">
              <span class="input-group-text" style="background-color: #e31575; color: white;"><i class="fa-solid fa-id-card"></i></span>
              <input type="id" class="form-input" name="id" placeholder="ID NO" required>
            </div>
          </div>
        </div>

        <div class="form-column">
          <div class="mb-3" style="margin: 0;">
            <label class="form-label">PASSWORD:</label>
            <div class="input-group">
              <span class="input-group-text" style="background-color: #e31575; color: white;"><i class="fas fa-lock"></i></span>
              <input type="password" id="password" class="form-input" name="password" placeholder="PASSWORD" required>
            </div>
            <!-- ✅ Live Password Validation Requirements -->
          <div class="password-requirements">
            <div class="requirement-item" id="req-length">
              <span class="check-icon">✓</span>
              <span>Contain 8 to 30 characters</span>
            </div>
            <div class="requirement-item" id="req-case">
              <span class="check-icon">✓</span>
              <span>Contain both lower and uppercase letters</span>
            </div>
            <div class="requirement-item" id="req-number">
              <span class="check-icon">✓</span>
              <span>At least 1 Digit</span>
            </div>
          </div>

          </div>

          
          
          <div class="mb-4">
            <label class="form-label">CONFIRM PASSWORD:</label>
            <div class="input-group">
              <span class="input-group-text" style="background-color: #e31575; color: white;"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-input" name="confirm_password" placeholder="CONFIRM PASSWORD" required>
            </div>
          </div>
        </div>
      </form>

      <div class="form-actions">
        <button type="submit" class="button signup-button" style="margin-bottom: 0.7rem;">SIGN UP</button>
        <div class="login-link">Already have account? <a href="{{ route('login') }}">Log In</a></div>
      </div>
    </div>
  </main>

  <div id="circle1"></div>
  <div id="circle2"></div>
  <div><img src="{{asset('images/Bagong_Pilipinas.png')}}" alt="Bagong Pilipinas" class="footer-logo"></div>

  <!-- Password Validation Script -->
  <script>
    const passwordInput = document.getElementById('password');
    const lengthReq = document.getElementById('req-length');
    const caseReq = document.getElementById('req-case');
    const numberReq = document.getElementById('req-number');


    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Check length (8 to 30 characters)
        if (password.length >= 8 && password.length <= 30) {
            lengthReq.classList.add('valid');
        } else {
            lengthReq.classList.remove('valid');
        }
        
        // Check for both lower and uppercase letters
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            caseReq.classList.add('valid');
        } else {
            caseReq.classList.remove('valid');
        }
        
        // Check for at least 1 number
        if (/\d/.test(password)) {
            numberReq.classList.add('valid');
        } else {
            numberReq.classList.remove('valid');
        }
        

    });
  </script>
</body>
</html>