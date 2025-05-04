<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Delete Account - Zeacut</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      max-width: 500px;
      margin: auto;
      background-color: #f9f9f9;
    }

    h1 {
      color: #c0392b;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input[type="text"], input[type="tel"] {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #e74c3c;
      color: #fff;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }

    .message {
      margin-top: 20px;
      font-weight: bold;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>

  <h1>Delete Your Account</h1>
  <p>To request account deletion, please enter your registered mobile number.</p>

  <form id="deleteAccountForm">
    <label for="phone">Mobile Number:</label>
    <input type="tel" id="phone" name="phone" placeholder="Enter mobile number" required>

    <label for="confirmPhone">Confirm Mobile Number:</label>
    <input type="tel" id="confirmPhone" name="confirmPhone" placeholder="Re-enter mobile number" required>

    <button type="submit">Send Request</button>
  </form>

  <div id="message" class="message"></div>

  <script>
    document.getElementById("deleteAccountForm").addEventListener("submit", function(event) {
      event.preventDefault();

      const phone = document.getElementById("phone").value.trim();
      const confirmPhone = document.getElementById("confirmPhone").value.trim();
      const messageDiv = document.getElementById("message");

      if (phone === "" || confirmPhone === "") {
        messageDiv.textContent = "Both fields are required.";
        messageDiv.className = "message error";
        return;
      }

      if (phone === confirmPhone) {
        messageDiv.textContent = "Account deletion request sent successfully to the Technical team.";
        messageDiv.className = "message success";
      } else {
        messageDiv.textContent = "Phone numbers do not match. Please try again.";
        messageDiv.className = "message error";
      }
    });
  </script>
</body>
</html>
