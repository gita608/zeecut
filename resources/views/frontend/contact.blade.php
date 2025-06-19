<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - Zeacut</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: slideUp 0.8s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .header {
      background: linear-gradient(135deg, #ff6b6b, #ee5a24);
      padding: 40px 30px;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      position: relative;
      z-index: 1;
    }

    .header p {
      font-size: 1.1rem;
      opacity: 0.9;
      position: relative;
      z-index: 1;
    }

    .form-container {
      padding: 40px 30px;
    }

    .message {
      margin-bottom: 25px;
      padding: 18px;
      border-radius: 12px;
      font-weight: 600;
      text-align: center;
      display: none;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .success {
      background: linear-gradient(135deg, #00b894, #00a085);
      color: white;
      box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
    }

    .error {
      background: linear-gradient(135deg, #ff6b6b, #ee5a24);
      color: white;
      box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #2d3436;
      font-size: 0.95rem;
    }

    input[type="text"], 
    input[type="email"], 
    input[type="tel"], 
    textarea {
      width: 100%;
      padding: 16px 20px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #fafafa;
    }

    input:focus, 
    textarea:focus {
      outline: none;
      border-color: #667eea;
      background: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    textarea {
      resize: vertical;
      min-height: 120px;
      font-family: inherit;
    }

    .submit-btn {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      border: none;
      padding: 18px 40px;
      font-size: 18px;
      font-weight: 600;
      border-radius: 12px;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
      position: relative;
      overflow: hidden;
    }

    .submit-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .submit-btn:hover::before {
      left: 100%;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .required {
      color: #ff6b6b;
    }

    .contact-info {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 30px;
      text-align: center;
      border-top: 1px solid #dee2e6;
    }

    .contact-info h3 {
      color: #2d3436;
      margin-bottom: 20px;
      font-size: 1.4rem;
      font-weight: 600;
    }

    .contact-methods {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #636e72;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .contact-item:hover {
      color: #667eea;
    }

    .contact-icon {
      width: 20px;
      height: 20px;
      fill: currentColor;
    }

    @media (max-width: 640px) {
      .header h1 {
        font-size: 2rem;
      }
      
      .form-container {
        padding: 30px 20px;
      }
      
      .contact-methods {
        flex-direction: column;
        gap: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Contact Zeacut</h1>
      <p>Get in touch with our team - we're here to help with your shopping experience!</p>
    </div>

    <div class="form-container">
      <div id="message" class="message"></div>

      <form id="contactForm">
        <div class="form-group">
          <label for="name">Full Name <span class="required">*</span></label>
          <input type="text" id="name" name="name" placeholder="Enter your full name" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address <span class="required">*</span></label>
          <input type="email" id="email" name="email" placeholder="Enter your email address" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" placeholder="Enter your phone number (optional)">
        </div>

        <div class="form-group">
          <label for="message">Message <span class="required">*</span></label>
          <textarea id="messageText" name="message" placeholder="Tell us about your order, product inquiry, or how we can help you..." required></textarea>
        </div>

        <button type="submit" class="submit-btn">Send Message</button>
      </form>
    </div>

    <div class="contact-info">
      <h3>Get in Touch</h3>
      <div class="contact-methods">
        <div class="contact-item">
          <svg class="contact-icon" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
          </svg>
          support@zeacut.com
        </div>
        <div class="contact-item">
          <svg class="contact-icon" viewBox="0 0 24 24">
            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
          </svg>
          +91 95395 26532
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("contactForm").addEventListener("submit", function(event) {
      event.preventDefault();

      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const messageText = document.getElementById("messageText").value.trim();
      const messageDiv = document.getElementById("message");

      // Reset message display
      messageDiv.style.display = "none";

      // Validation
      if (name === "" || email === "" || messageText === "") {
        showMessage("Please fill in all required fields to continue.", "error");
        return;
      }

      // Email validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showMessage("Please enter a valid email address.", "error");
        return;
      }

      // Simulate form submission
      showMessage("🎉 Thank you for reaching out! Our team will respond to your message within 24 hours.", "success");
      
      // Reset form
      document.getElementById("contactForm").reset();
    });

    function showMessage(text, type) {
      const messageDiv = document.getElementById("message");
      messageDiv.textContent = text;
      messageDiv.className = "message " + type;
      messageDiv.style.display = "block";
      
      // Scroll to message
      messageDiv.scrollIntoView({ behavior: "smooth", block: "center" });
    }
  </script>
</body>
</html>