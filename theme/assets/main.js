/* ASOINCO — tema (Pulso21) */
(function () {
  // Ruta a los assets del tema (inyectada por functions.php).
  var AB = window.ASOINCO_ASSETS || '/wp-content/themes/asoinco/assets/';

  /* HERO SLIDER */
  var slides = [].slice.call(document.querySelectorAll('.hero-slide'));
  var dotsWrap = document.getElementById('dots');
  if (slides.length && dotsWrap) {
    var cur = 0;
    slides.forEach(function (_, i) {
      var b = document.createElement('button');
      if (i === 0) b.className = 'active';
      b.onclick = function () { go(i); };
      dotsWrap.appendChild(b);
    });
    var dots = [].slice.call(dotsWrap.children);
    var go = function (i) {
      slides[cur].classList.remove('active'); dots[cur].classList.remove('active');
      cur = (i + slides.length) % slides.length;
      slides[cur].classList.add('active'); dots[cur].classList.add('active');
    };
    var next = document.getElementById('next');
    var prev = document.getElementById('prev');
    if (next) next.onclick = function () { go(cur + 1); };
    if (prev) prev.onclick = function () { go(cur - 1); };
    var timer = setInterval(function () { go(cur + 1); }, 6000);
    var heroEl = document.querySelector('.hero');
    if (heroEl) {
      heroEl.addEventListener('mouseenter', function () { clearInterval(timer); });
      heroEl.addEventListener('mouseleave', function () { timer = setInterval(function () { go(cur + 1); }, 6000); });
    }
  }

  /* HAMBURGER */
  var hamb = document.getElementById('hamb'), menu = document.getElementById('navMenu');
  if (hamb && menu) {
    hamb.onclick = function () { hamb.classList.toggle('open'); menu.classList.toggle('open'); };
    menu.querySelectorAll('a').forEach(function (a) {
      a.onclick = function () { hamb.classList.remove('open'); menu.classList.remove('open'); };
    });
  }

  /* FORMULARIOS DEMO — muestran mensaje de éxito sin backend (temporal hasta conectar plugin de formularios) */
  document.querySelectorAll('form[data-demo]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = f.parentNode.querySelector('.form-ok');
      f.style.display = 'none';
      if (ok) { ok.classList.add('show'); ok.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    });
  });

  /* SOCIOS — el carrusel se genera en el servidor (front-page.php), desde el CPT "socio". */

  /* ALIADOS — logos reales con respaldo de texto */
  var allies = [{ n: 'CMP', k: 'cmp' }, { n: 'CORFO', k: 'corfo' }, { n: 'UCN', k: 'ucn' }, { n: 'Consejo Minero', k: 'consejominero' }, { n: 'Itaú', k: 'itau' }, { n: 'GTD', k: 'gtd' }, { n: 'Andes Iron', k: 'andesiron' }];
  var ar = document.getElementById('allies');
  if (ar) {
    allies.forEach(function (a) {
      var d = document.createElement('div'); d.className = 'ally';
      var img = new Image(); img.alt = a.n; img.src = AB + 'ally-' + a.k + '.png';
      img.onerror = function () { d.innerHTML = '<span class="txt">' + a.n + '</span>'; };
      d.appendChild(img); ar.appendChild(d);
    });
  }

  /* INSTAGRAM — fotos reales publicadas por ASOINCO */
  var igs = [AB + 'ig-1.jpg', AB + 'ig-2.jpg', AB + 'ig-3.jpg', AB + 'ig-4.jpg', AB + 'ig-5.jpg', AB + 'ig-6.jpg'];
  var ig = document.getElementById('igGrid'), fig = document.getElementById('footIg');
  var igIcon = '<svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><line x1="17.5" y1="6.5" x2="17.5" y2="6.51"/></svg>';
  igs.forEach(function (f) {
    if (ig) {
      var a = document.createElement('div'); a.className = 'ig-cell';
      a.innerHTML = '<img src="' + f + '" alt=""><div class="ov">' + igIcon + '</div>'; ig.appendChild(a);
    }
    if (fig) {
      var fa = document.createElement('a'); fa.href = 'https://instagram.com/asoinco.ag';
      fa.innerHTML = '<img src="' + f + '" alt="">'; fig.appendChild(fa);
    }
  });
})();
