<!-- Loading Overlay -->
<div id="loadingOverlay">
  <div class="loader">
    <span class="letter">S</span>
    <span class="letter">T</span>
    <span class="letter">R</span>
    <span class="letter">A</span>
    <span class="letter">M</span>
    <span class="letter">M</span>
  </div>
</div>

<style>
#loadingOverlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.2);
  backdrop-filter: blur(4px);
  display: none;
  align-items: center; 
  justify-content: center; 
  z-index: 9999;
}

.loader {
  font-size: 48px;
  font-weight: bold;
  color: #00C584;
  font-family: Arial, sans-serif;
  display: flex;
  width: 100%;
  height: 100%; 
  text-align: center;
  justify-content: center;
  align-items: center; 
}



.letter {
  opacity: 0;
  animation: fadeIn 1.5s infinite alternate;
}


@keyframes fadeIn {
  0% { opacity: 0; transform: scale(0.8); }
  100% { opacity: 1; transform: scale(1); }
}

.letter:nth-child(1) { animation-delay: 0s; }
.letter:nth-child(2) { animation-delay: 0.2s; }
.letter:nth-child(3) { animation-delay: 0.4s; }
.letter:nth-child(4) { animation-delay: 0.6s; }
.letter:nth-child(5) { animation-delay: 0.8s; }
.letter:nth-child(6) { animation-delay: 1s; }
</style>