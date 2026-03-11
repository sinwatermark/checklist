<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
<title>Watermark Hotel - Security Checklist</title>

<script src="https://unpkg.com/html5-qrcode"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box}

html,body{
  width:100%;
  height:100%;
  overflow:hidden;
}

body{
  background:#0f172a;
  color:white;
  font-family:Arial,sans-serif;
  display:flex;
  flex-direction:column;
  align-items:center;
}

/* HEADER */
.header{
  padding-top:30px;
  text-align:center;
}
.header h1{font-size:34px}
.header h2{font-size:26px;opacity:.8;font-weight:normal}

/* MAIN */
.main{
  flex:1;
  width:100%;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:18px;
}

/* CAMERA */
.camera-box{
  width:280px;
  height:280px;
  background:black;
  border-radius:18px;
  overflow:hidden;
  display:none;
}

#reader, #reader video{
  width:100%!important;
  height:100%!important;
  object-fit:cover!important;
}

/* BUTTONS */
.scan-btn{
  background:#22c55e;
  color:#022c22;
  font-size:22px;
  padding:26px;
  border:none;
  border-radius:16px;
  width:85%;
  max-width:320px;
}

.actions{
  display:none;
  gap:12px;
}

.actions button{
  padding:14px 22px;
  font-size:16px;
  border:none;
  border-radius:12px;
}

.open{background:#22c55e;color:#022c22}
.retry{background:#facc15;color:#422006}
.cancel{background:#ef4444;color:white}

/* FOOTER */
.footer{
  padding-bottom:10px;
  font-size:12px;
  opacity:.6;
}
</style>
</head>

<body>

<div class="header">
  <h1>Watermark Hotel</h1>
  <h2>Security Checklist</h2>
</div>

<div class="main">

  <button id="scanBtn" class="scan-btn" onclick="startScan()">
    Tap Here to Scan QR
  </button>

  <div class="camera-box" id="cameraBox">
    <div id="reader"></div>
  </div>

  <div class="actions" id="actions">
    <button id="openBtn" class="open" onclick="openLink()">Start Checklist</button>
    <button id="retryBtn" class="retry" onclick="reloadPage()">Retry</button>
    <button id="cancelBtn" class="cancel" onclick="reloadPage()">Cancel</button>
  </div>

</div>

<div class="footer">© Copyright by SIN</div>

<script>
let qr;
let scanState = "IDLE"; // IDLE | SUCCESS | FAIL
let pendingUrl = null;
let timeout;

function resetUI(){
  actions.style.display = "none";
  openBtn.style.display = "none";
  retryBtn.style.display = "none";
  cancelBtn.style.display = "none";
}

function startScan(){
  scanState = "IDLE";
  pendingUrl = null;

  scanBtn.style.display = "none";
  cameraBox.style.display = "block";
  resetUI();

  qr = new Html5Qrcode("reader");

  qr.start(
    { facingMode:"environment" },
    { fps:5, aspectRatio:1, disableFlip:true },
    onScanSuccess,
    ()=>{}
  );

  timeout = setTimeout(()=>{
    if(scanState === "IDLE"){
      scanState = "FAIL";
      showFailActions();
    }
  },15000);
}

function onScanSuccess(text){
  if(scanState !== "IDLE") return;

  scanState = "SUCCESS";
  clearTimeout(timeout);

  if(!/^https?:\/\//i.test(text)){
    text = "https://" + text;
  }

  pendingUrl = text;

  qr.stop();

  showSuccessAction();
}

/* UI STATES */
function showSuccessAction(){
  resetUI();
  actions.style.display = "flex";
  openBtn.style.display = "inline-block";
}

function showFailActions(){
  resetUI();
  actions.style.display = "flex";
  retryBtn.style.display = "inline-block";
  cancelBtn.style.display = "inline-block";
}

/* ACTIONS */
function openLink(){
  if(pendingUrl){
    window.location.href = pendingUrl;
  }
}

function reloadPage(){
  location.reload();
}
</script>

</body>
</html>
