<!DOCTYPE html>
<html>
    <head>
        <title>**Tris** in PHP</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h1>**Gioca a Tris!** (Tu: X, Computer: O)</h1>

        <?php
        session_start();

        $username = $_SESSION['username'] ?? '';

        // Input nome
        if (empty($username)) {
            echo '<form method="POST">
                <label style="font-size: 1.8em;">Inserisci il tuo nome:</label> <br><br>
                <input type="text" name="username" placeholder="es. Milena" required>
                <br><br> 
                <button type="submit">🚀 Inizia Gioco!</button>
            </form>';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $_SESSION['username'] = htmlspecialchars($_POST['username']);
                $_SESSION['board'] = [['', '', ''], ['', '', ''], ['', '', '']];
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
            exit;
        }

        // Board e funzioni
        $board = $_SESSION['board'] ?? [['', '', ''], ['', '', ''], ['', '', '']];
        $_SESSION['board'] = $board;

        function checkWin($b, $p) {
            for ($i = 0; $i < 3; $i++) {
                if ($b[$i][0] == $p && $b[$i][1] == $p && $b[$i][2] == $p) return true;
                if ($b[0][$i] == $p && $b[1][$i] == $p && $b[2][$i] == $p) return true;
            }
            if ($b[0][0] == $p && $b[1][1] == $p && $b[2][2] == $p) return true;
            if ($b[0][2] == $p && $b[1][1] == $p && $b[2][0] == $p) return true;
            return false;
        }

        function checkTie($b) {
            foreach ($b as $r) foreach ($r as $c) if ($c == '') return false;
            return true;
        }

        // Mossa giocatore
        if (isset($_GET['row'], $_GET['col'])) {
            $r = (int)$_GET['row']; $c = (int)$_GET['col'];
            if ($board[$r][$c] == '') {
                $board[$r][$c] = 'X';
                if (checkWin($board, 'X')) {
                    echo "<div class='message' style='color:#4CAF50; background:rgba(76,175,80,0.3);'>**{$username} hai vinto!** 🎉</div>";
                    echo "<a href='?reset=1'><button>🎮 Nuova Partita</button></a>"; exit;
                }
                if (checkTie($board)) {
                    echo "<div class='message' style='color:#FF9800; background:rgba(255,152,0,0.3);'>**{$username}, pareggio!** 🤝</div>";
                    echo "<a href='?reset=1'><button>🔄 Nuova Partita</button></a>"; exit;
                }
                // Computer
                do { $cr = rand(0,2); $cc = rand(0,2); } while ($board[$cr][$cc] != '');
                $board[$cr][$cc] = 'O';
                if (checkWin($board, 'O')) {
                    echo "<div class='message' style='color:#f44336; background:rgba(244,67,54,0.3);'>**{$username} hai perso!** 😔</div>";
                    echo "<a href='?reset=1'><button>🔄 Nuova Partita</button></a>"; exit;
                }
                if (checkTie($board)) {
                    echo "<div class='message' style='color:#FF9800; background:rgba(255,152,0,0.3);'>**{$username}, pareggio!** 🤝</div>";
                    echo "<a href='?reset=1'><button>🔄 Nuova Partita</button></a>"; exit;
                }
                $_SESSION['board'] = $board;
            }
        }

        // Reset
        if (isset($_GET['reset'])) {
            $_SESSION['board'] = [['', '', ''], ['', '', ''], ['', '', '']];
            header('Location: ' . $_SERVER['PHP_SELF']); exit;
        }
        ?>

        <div class="welcome">Benvenuto, <?php echo $username; ?>
        ! 👋 Clicca su una casella vuota per giocare.</div>

        <table>
        <?php for ($i=0; $i<3; $i++): ?>
        <tr>
        <?php for ($j=0; $j<3; $j++): ?>
        <td class="<?php echo $board[$i][$j]; ?>" onclick="location.href='?row=<?php echo $i;?>&col=<?php echo $j;?>'">
        <?php echo $board[$i][$j]; ?>
        </td>
        <?php endfor; ?>
        </tr>
        <?php endfor; ?>
        </table>

        <div>
        <a href="?reset=1"><button>🔄 Nuova Partita</button></a>
        <a href="?logout=1"><button style="background: linear-gradient(145deg, #f44336, #d32f2f);">
            👤 Cambia Nome</button></a>
        </div>

        <?php if (isset($_GET['logout'])) { unset($_SESSION['username']); session_destroy(); header('Location: ' . $_SERVER['PHP_SELF']); exit; } ?>
        
    </body>
</html>