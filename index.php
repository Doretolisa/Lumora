<?php
session_start();

$logado = isset($_SESSION['usuario_id']);
$nome   = $logado ? $_SESSION['usuario_nome'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lumora</title>

<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:'Space Grotesk',sans-serif;
background:#0a0f1e;
color:#e0e0e0;
overflow-x:hidden;
}

#particles-js{
position:fixed;
width:100%;
height:100%;
top:0;
left:0;
z-index:-1;
}

body::before{
content:"";
position:fixed;
width:100%;
height:100%;
background-image:
linear-gradient(rgba(0,150,255,0.05) 1px, transparent 1px),
linear-gradient(90deg, rgba(0,150,255,0.05) 1px, transparent 1px);
background-size:50px 50px;
z-index:-1;
}

.container{
max-width:1200px;
margin:auto;
padding:30px;
}

header{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 0;
border-bottom:1px solid rgba(0,180,255,0.3);
}

.logo{
height:70px;
filter:drop-shadow(0 0 15px rgba(255,215,0,0.5));
}

.logo-area{
display:flex;
align-items:center;
}

nav ul{
display:flex;
gap:30px;
list-style:none;
align-items:center;
}

nav a{
color:white;
text-decoration:none;
font-weight:500;
}

/* ── BOTÃO ENTRAR ── */
.btn-login {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 24px;
  background: linear-gradient(135deg, rgba(0,180,255,0.15), rgba(0,100,200,0.25));
  border: 1px solid rgba(0,180,255,0.6);
  border-radius: 30px;
  color: #00b4ff;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 0.9rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.btn-login::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(0,180,255,0.3), rgba(0,100,200,0.4));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.btn-login:hover {
  border-color: #00b4ff;
  color: #fff;
  box-shadow: 0 0 20px rgba(0,180,255,0.4), 0 0 40px rgba(0,180,255,0.15);
  transform: translateY(-1px);
}

.btn-login:hover::before { opacity: 1; }

.btn-login i,
.btn-login span {
  position: relative;
  z-index: 1;
}

/* ── USUÁRIO LOGADO ── */
.btn-perfil {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 8px 20px;
  background: rgba(0,180,255,0.08);
  border: 1px solid rgba(0,180,255,0.3);
  border-radius: 30px;
  color: #c8dff5;
  font-family: 'Space Grotesk', sans-serif;
  font-size: 0.88rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.25s ease;
}

.btn-perfil:hover {
  background: rgba(0,180,255,0.15);
  border-color: rgba(0,180,255,0.6);
  color: #fff;
  box-shadow: 0 0 16px rgba(0,180,255,0.25);
  transform: translateY(-1px);
}

.btn-perfil .perfil-icon {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0055cc, #00b4ff);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  color: #fff;
  flex-shrink: 0;
}

/* ── HERO ── */
.hero{
display:grid;
grid-template-columns:1fr 1fr;
align-items:center;
margin-top:80px;
gap:40px;
}

.hero h1{
font-size:3.5rem;
line-height:1.1;
}

.blue{ color:#00b4ff; }
.yellow{ color:#ffd700; }

.hero p{
margin-top:20px;
color:#b0b0d0;
font-size:1.1rem;
}

.visual{
display:flex;
justify-content:center;
position:relative;
}

.modelo-container{
position:relative;
width:300px;
height:300px;
display:flex;
align-items:center;
justify-content:center;
}

.core{
width:300px;
height:300px;
border-radius:50%;
display:flex;
align-items:center;
justify-content:center;
background:radial-gradient(circle, rgba(0,180,255,0.2), transparent);
animation:pulse 3s infinite alternate;
position:absolute;
}

.inner{
width:150px;
height:150px;
border-radius:50%;
background:radial-gradient(circle, rgba(0,180,255,0.2), transparent);
animation:inner 3s infinite alternate;
}

@keyframes pulse{ 0%{transform:scale(.95)} 100%{transform:scale(1.05)} }
@keyframes inner{ 0%{transform:scale(.9)} 100%{transform:scale(1.1)} }

#modelo3d{
width:300px;
height:300px;
cursor:pointer;
position:relative;
z-index:2;
}

/* ── CARDS ── */
.cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:30px;
margin-top:120px;
}

.card{
background:rgba(20,25,45,0.6);
padding:30px;
border-radius:20px;
text-align:center;
border:1px solid rgba(0,180,255,0.3);
}

.card i{
font-size:2.5rem;
margin-bottom:15px;
color:#ebcd04;
}

.card h3{ margin-bottom:10px; }

footer{
text-align:center;
margin-top:100px;
padding:30px;
border-top:1px solid rgba(0,180,255,0.3);
color:#8080a0;
}

@media(max-width:900px){
.hero{ grid-template-columns:1fr; text-align:center; }
.cards{ grid-template-columns:1fr; }
}
</style>
</head>

<body>

<div id="particles-js"></div>

<div class="container">

  <header>
    <div class="logo-area">
      <img src="logo.png" class="logo">
    </div>
    <nav>
      <ul>
        <li>
          <?php if ($logado): ?>
            <a href="perfil.php" class="btn-perfil">
              <div class="perfil-icon"><i class="fas fa-user"></i></div>
              <span>Bem-vindo, <?= htmlspecialchars($nome) ?></span>
            </a>
            <a href="logout.php" class="btn-login" style="margin-left:10px;">
              <i class="fas fa-sign-out-alt"></i>
              <span>Sair</span>
            </a>
          <?php else: ?>
            <a href="login.php" class="btn-login">
              <i class="fas fa-user"></i>
              <span>Entrar</span>
            </a>
          <?php endif; ?>
        </li>
      </ul>
    </nav>
  </header>

  <section class="hero">
    <div>
      <h1>
        <span class="blue">Energia</span> que nasce da <br>
        <span class="yellow">natureza</span> e da <span class="blue">chuva</span>
      </h1>
      <p>
        Sistema híbrido inteligente de biomassa e energia pluvial. Feito de materiais reciclaveis pensado com carinho para o seu lar.
      </p>
    </div>

    <div class="visual">
      <div class="modelo-container">
        <div class="core">
          <div class="inner"></div>
        </div>
        <div id="modelo3d"></div>
      </div>
    </div>
  </section>

  <section class="cards">
    <div class="card">
      <i class="fas fa-leaf"></i>
      <h3>Biomassa</h3>
      <p>Resíduos orgânicos convertidos em energia limpa.</p>
    </div>
    <div class="card">
      <i class="fas fa-cloud-rain"></i>
      <h3>Energia da Chuva</h3>
      <p>Turbinas que aproveitam água da calha.</p>
    </div>
    <div class="card">
      <i class="fas fa-battery-full"></i>
      <h3>Armazenamento</h3>
      <p>Baterias inteligentes para uso contínuo.</p>
    </div>
  </section>

  <footer>
    © 2026 Lumora
  </footer>

</div>

<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script>
particlesJS("particles-js",{
  particles:{
    number:{value:80},
    color:{value:"#00b4ff"},
    shape:{type:"circle"},
    opacity:{value:0.5},
    size:{value:3},
    line_linked:{enable:true,distance:150,color:"#00b4ff",opacity:0.2,width:1},
    move:{enable:true,speed:2}
  },
  interactivity:{
    events:{onhover:{enable:true,mode:"repulse"}},
    modes:{repulse:{distance:120}}
  }
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/OBJLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<script>
const scene = new THREE.Scene();
scene.background = null;

const camera = new THREE.PerspectiveCamera(75, 1, 0.1, 1000);
camera.position.set(2.5, 1.5, 4);

const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
renderer.setSize(300, 300);
renderer.setPixelRatio(window.devicePixelRatio);
document.getElementById("modelo3d").appendChild(renderer.domElement);

const controls = new THREE.OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.autoRotate = true;
controls.autoRotateSpeed = 2;
controls.enableZoom = false;
controls.enablePan = false;

const ambientLight = new THREE.AmbientLight(0x404060);
scene.add(ambientLight);

const dirLight = new THREE.DirectionalLight(0xffffff, 1);
dirLight.position.set(2, 5, 3);
scene.add(dirLight);

let modelo = null;

const loader = new THREE.OBJLoader();
loader.load("whiite_mesh.obj",
  function(object) {
    modelo = object;
    modelo.scale.set(1.5, 1.5, 1.5);
    modelo.traverse(function(child) {
      if (child.isMesh) {
        child.material = new THREE.MeshStandardMaterial({
          color: 0x88aaff,
          emissive: 0x112244,
          roughness: 0.3,
          metalness: 0.1
        });
      }
    });
    scene.add(modelo);
  },
  undefined,
  function() {
    const geometry = new THREE.CylinderGeometry(1, 1, 2, 8);
    const material = new THREE.MeshStandardMaterial({ color: 0x88aaff });
    modelo = new THREE.Mesh(geometry, material);
    scene.add(modelo);
  }
);

function animate() {
  requestAnimationFrame(animate);
  controls.update();
  renderer.render(scene, camera);
}

animate();
</script>

</body>
</html>
