<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim Formu</title>
</head>
<body>
    <h1>İletişim Formu</h1>
    <form action="process.php" method="post">
        <label for="name">Kullanıcı Adı:</label>
        <input type="text" id="name" name="name" required>
        <br><br>

        <label for="email">E-posta:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="message">Mesaj:</label>
        <textarea id="message" name="message" rows="4" required></textarea>
        <br><br>

        <input type="submit" value="Gönder">
    </form>
</body>
</html>
