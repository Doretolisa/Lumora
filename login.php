<?php
session_start();

$db_host = 'localhost'; //mudar para sql302.infinityfree.com
$db_name = 'if0_41611802_lumora';
$db_user = 'root'; //if0_41611802
$db_pass = ''; //3YELumora

$erro_login    = '';
$sucesso_login = '';
$erro_cad      = '';
$sucesso_cad   = '';
$aba_ativa     = 'login';

function conectar($host, $name, $user, $pass) {
    return new PDO(
        "mysql:host=$host;dbname=$name;charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'login') {
    $aba_ativa = 'login';
    $email = trim($_POST['email_login'] ?? '');
    $senha = $_POST['senha_login'] ?? '';

    if (empty($email) || empty($senha)) {
        $erro_login = 'Preencha todos os campos.';
    } else {
        try {
            $pdo  = conectar($db_host, $db_name, $db_user, $db_pass);
            $stmt = $pdo->prepare("SELECT id, nome, senha_hash FROM usuarios WHERE email = :e LIMIT 1");
            $stmt->execute([':e' => $email]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($u && password_verify($senha, $u['senha_hash'])) {
                $_SESSION['usuario_id']   = $u['id'];
                $_SESSION['usuario_nome'] = $u['nome'];
                // Registrar o acesso na tabela
                $log = $pdo->prepare("INSERT INTO log_acessos (usuario_id, ip, user_agent) VALUES (:id, :ip, :ua)");
                $log->execute([
                    ':id' => $u['id'],
                    ':ip' => $_SERVER['REMOTE_ADDR'],
                    ':ua' => $_SERVER['HTTP_USER_AGENT']
                ]);
                $sucesso_login = 'Bem-vindo, ' . htmlspecialchars($u['nome']) . '!';
            } else {
                $erro_login = 'E-mail ou senha incorretos.';
            }
        } catch (PDOException $e) {
            $erro_login = 'Erro de conexão com o banco de dados.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'cadastro') {
    $aba_ativa = 'cadastro';
    $nome  = trim($_POST['nome']      ?? '');
    $email = trim($_POST['email_cad'] ?? '');
    $senha = $_POST['senha_cad']      ?? '';
    $conf  = $_POST['confirma_senha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha) || empty($conf)) {
        $erro_cad = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro_cad = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro_cad = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $conf) {
        $erro_cad = 'As senhas não coincidem.';
    } else {
        try {
            $pdo = conectar($db_host, $db_name, $db_user, $db_pass);
            $chk = $pdo->prepare("SELECT id FROM usuarios WHERE email = :e LIMIT 1");
            $chk->execute([':e' => $email]);
            if ($chk->fetch()) {
                $erro_cad = 'Este e-mail já está cadastrado.';
            } else {
                $hash = password_hash($senha, PASSWORD_BCRYPT);
                $ins  = $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash) VALUES (:n, :e, :h)");
                $ins->execute([':n' => $nome, ':e' => $email, ':h' => $hash]);
                $sucesso_cad = 'Conta criada! Faça login para continuar.';
                $aba_ativa = 'login';
            }
        } catch (PDOException $e) {
            $erro_cad = 'Erro de conexão com o banco de dados.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lumora — Login</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Space Grotesk', sans-serif;
  background: #0a0f1e;
  color: #e0e0e0;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

body::before {
  content: "";
  position: fixed;
  inset: 0;
  background-image:
    linear-gradient(rgba(0,150,255,0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0,150,255,0.05) 1px, transparent 1px);
  background-size: 50px 50px;
  z-index: 0;
}

.orb {
  position: fixed;
  border-radius: 50%;
  filter: blur(80px);
  z-index: 0;
  pointer-events: none;
}
.orb-1 { width: 450px; height: 450px; background: rgba(0,100,255,0.12); top: -120px; left: -120px; }
.orb-2 { width: 350px; height: 350px; background: rgba(255,215,0,0.07); bottom: -100px; right: -100px; }

#particles-js { position: fixed; inset: 0; z-index: 0; }

/* ── Layout ── */
.wrap {
  position: relative;
  z-index: 10;
  width: 100%;
  max-width: 400px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.logo-link {
  display: flex;
  justify-content: center;
  margin-bottom: 32px;
}
.logo-link img {
  height: 180px;
  filter: drop-shadow(0 0 14px rgba(255,215,0,0.45));
}

/* ── Tabs ── */
.tabs {
  display: flex;
  width: 100%;
  border-bottom: 1px solid rgba(0,180,255,0.15);
  margin-bottom: 28px;
  gap: 0;
}
.tab-btn {
  flex: 1;
  background: none;
  border: none;
  border-bottom: 2px solid transparent;
  padding: 10px 0;
  color: #3a5070;
  font-family: 'Space Grotesk', sans-serif;
  font-size: .9rem;
  font-weight: 600;
  cursor: pointer;
  transition: color .2s, border-color .2s;
  margin-bottom: -1px;
}
.tab-btn.active {
  color: #fff;
  border-color: #00b4ff;
}

/* ── Card ── */
.card {
  width: 100%;
  background: rgba(14,20,42,0.85);
  border: 1px solid rgba(0,180,255,0.18);
  border-radius: 20px;
  backdrop-filter: blur(16px);
  overflow: hidden;
}

.panels { display: flex; transition: transform .4s cubic-bezier(.4,0,.2,1); width: 200%; }
.panels.show-cadastro { transform: translateX(-50%); }
.panel { width: 50%; padding: 32px 28px; flex-shrink: 0; }

/* ── Alerts ── */
.alert {
  padding: 10px 14px;
  border-radius: 10px;
  font-size: .84rem;
  margin-bottom: 20px;
  animation: fadeIn .3s ease;
}
.alert-erro    { background: rgba(255,60,60,0.1);  border: 1px solid rgba(255,60,60,0.3);  color: #ff8888; }
.alert-sucesso { background: rgba(0,220,100,0.1); border: 1px solid rgba(0,220,100,0.3); color: #55ffaa; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

/* ── Campos ── */
.field { margin-bottom: 16px; }
.field label { display: block; font-size: .75rem; font-weight: 600; color: #607090; margin-bottom: 6px; letter-spacing: .04em; text-transform: uppercase; }
.field input {
  width: 100%;
  background: rgba(0,15,50,0.5);
  border: 1px solid rgba(0,180,255,0.15);
  border-radius: 10px;
  padding: 11px 14px;
  color: #dde8ff;
  font-family: 'Space Grotesk', sans-serif;
  font-size: .9rem;
  outline: none;
  transition: border-color .2s;
}
.field input:focus { border-color: rgba(0,180,255,0.5); }

.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.forgot { text-align: right; margin-top: -8px; margin-bottom: 20px; }
.forgot a { font-size: .78rem; color: #3a5070; text-decoration: none; }
.forgot a:hover { color: #00b4ff; }

.strength-bar { margin-top: 6px; height: 3px; border-radius: 2px; background: rgba(255,255,255,0.07); overflow: hidden; }
.strength-fill { height: 100%; border-radius: 2px; width: 0; transition: width .3s, background .3s; }

/* ── Botão ── */
.btn-submit {
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #0055cc, #00b4ff);
  border: none;
  border-radius: 10px;
  color: #fff;
  font-family: 'Space Grotesk', sans-serif;
  font-size: .92rem;
  font-weight: 700;
  cursor: pointer;
  transition: opacity .2s, transform .15s;
  margin-top: 4px;
}
.btn-submit:hover { opacity: .88; transform: translateY(-1px); }

/* ── Rodapé do card ── */
.switch-link {
  text-align: center;
  margin-top: 20px;
  font-size: .82rem;
  color: #3a5070;
}
.switch-link button {
  background: none;
  border: none;
  color: #00b4ff;
  font-family: 'Space Grotesk', sans-serif;
  font-size: .82rem;
  font-weight: 700;
  cursor: pointer;
}

.back-link {
  margin-top: 18px;
  font-size: .8rem;
  color: #2a4060;
}
.back-link a { color: #3a6080; text-decoration: none; }
.back-link a:hover { color: #00b4ff; }

@media (max-width: 480px) {
  .panel { padding: 24px 18px; }
  .row-2 { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div id="particles-js"></div>

<div class="wrap">

  <a class="logo-link" href="index.php">
    <img src="logo.png" alt="Lumora">
  </a>

  <div class="tabs">
    <button class="tab-btn <?= $aba_ativa === 'login' ? 'active' : '' ?>" onclick="setAba('login')">Entrar</button>
    <button class="tab-btn <?= $aba_ativa === 'cadastro' ? 'active' : '' ?>" onclick="setAba('cadastro')">Cadastrar</button>
  </div>

  <div class="card">
    <div class="panels <?= $aba_ativa === 'cadastro' ? 'show-cadastro' : '' ?>" id="panels">

      <!-- LOGIN -->
      <div class="panel">

        <?php if ($erro_login): ?>
          <div class="alert alert-erro"><?= htmlspecialchars($erro_login) ?></div>
        <?php endif; ?>
        <?php if ($sucesso_login): ?>
          <div class="alert alert-sucesso" id="msg-sucesso"><?= htmlspecialchars($sucesso_login) ?> Redirecionando...</div>
        <?php endif; ?>
        <?php if ($sucesso_cad): ?>
          <div class="alert alert-sucesso"><?= htmlspecialchars($sucesso_cad) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
          <input type="hidden" name="acao" value="login">

          <div class="field">
            <label>E-mail</label>
            <input type="email" name="email_login" placeholder="seu@email.com"
              value="<?= htmlspecialchars($_POST['email_login'] ?? '') ?>" required autocomplete="email">
          </div>

          <div class="field">
            <label>Senha</label>
            <input type="password" name="senha_login" placeholder="••••••••" required autocomplete="current-password">
          </div>

          <div class="forgot"><a href="#">Esqueceu a senha?</a></div>

          <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <div class="switch-link">
          Não tem conta? <button onclick="setAba('cadastro')">Cadastre-se</button>
        </div>
      </div>

      <!-- CADASTRO -->
      <div class="panel">

        <?php if ($erro_cad): ?>
          <div class="alert alert-erro"><?= htmlspecialchars($erro_cad) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
          <input type="hidden" name="acao" value="cadastro">

          <div class="field">
            <label>Nome</label>
            <input type="text" name="nome" placeholder="Seu nome"
              value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required autocomplete="name">
          </div>

          <div class="field">
            <label>E-mail</label>
            <input type="email" name="email_cad" placeholder="seu@email.com"
              value="<?= htmlspecialchars($_POST['email_cad'] ?? '') ?>" required autocomplete="email">
          </div>

          <div class="row-2">
            <div class="field">
              <label>Senha</label>
              <input type="password" name="senha_cad" placeholder="••••••••"
                required autocomplete="new-password" oninput="avaliarForca(this.value)">
              <div class="strength-bar"><div class="strength-fill" id="sfill"></div></div>
            </div>
            <div class="field">
              <label>Confirmar</label>
              <input type="password" name="confirma_senha" placeholder="••••••••"
                required autocomplete="new-password">
            </div>
          </div>

          <button type="submit" class="btn-submit">Criar conta</button>
        </form>

        <div class="switch-link">
          Já tem conta? <button onclick="setAba('login')">Entrar</button>
        </div>
      </div>

    </div>
  </div>

  <div class="back-link">
    <a href="index.php">← Voltar ao site</a>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script>
particlesJS("particles-js", {
  particles: {
    number: { value: 50 },
    color: { value: "#00b4ff" },
    shape: { type: "circle" },
    opacity: { value: 0.3 },
    size: { value: 2 },
    line_linked: { enable: true, distance: 140, color: "#00b4ff", opacity: 0.15, width: 1 },
    move: { enable: true, speed: 1.5 }
  },
  interactivity: {
    events: { onhover: { enable: true, mode: "repulse" } },
    modes: { repulse: { distance: 100 } }
  }
});

<?php if ($sucesso_login): ?>
setTimeout(() => { window.location.href = 'index.php'; }, 3000);
<?php endif; ?>

function setAba(aba) {
  const panels = document.getElementById('panels');
  const btns   = document.querySelectorAll('.tab-btn');
  if (aba === 'cadastro') {
    panels.classList.add('show-cadastro');
    btns[0].classList.remove('active');
    btns[1].classList.add('active');
  } else {
    panels.classList.remove('show-cadastro');
    btns[0].classList.add('active');
    btns[1].classList.remove('active');
  }
}

function avaliarForca(val) {
  const fill = document.getElementById('sfill');
  let score = 0;
  if (val.length >= 6)  score++;
  if (val.length >= 10) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const cores = ['#ff4444','#ff8800','#ffcc00','#88dd00','#00cc66'];
  const pcts  = [15, 35, 55, 75, 100];
  fill.style.width      = (val.length ? pcts[Math.min(score, 4)] : 0) + '%';
  fill.style.background = cores[Math.min(score, 4)];
}
</script>

</body>
</html>
