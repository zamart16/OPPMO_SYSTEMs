<style>
    /* LOADING MODAL ANIMATION */
/* Logo pulse */
.logo-animate {
  animation: logoPulse 2.5s infinite ease-in-out;
}
@keyframes logoPulse {
  0%,100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

/* Smoke bubbles (optional for layered effect) */
.smoke {
  position: absolute;
  width: 250px;
  height: 250px;
  border-radius: 50%;
  filter: blur(60px);
  opacity: 0.6;
  animation: smokeExpand 6s infinite ease-out;
}

@keyframes smokeExpand {
  0% { transform: scale(0.4); opacity: 0.8; }
  100% { transform: scale(1.8); opacity: 0; }
}

.smoke1 { background: radial-gradient(circle, rgba(255, 94, 0, 0.959), transparent 70%); animation-delay: 0s; }
.smoke2 { background: radial-gradient(circle, rgba(253, 98, 26, 0.9), transparent 70%); animation-delay: 1.5s; }
.smoke3 { background: radial-gradient(circle, rgba(255, 129, 40, 0.9), transparent 70%); animation-delay: 3s; }
.smoke4 { background: radial-gradient(circle, rgba(241, 108, 31, 0.9), transparent 70%); animation-delay: 4.5s; }

/* Full screen spreading smoke */
.global-smoke {
  position: absolute;
  width: 200vmax;
  height: 200vmax;
  background: radial-gradient(circle at center,
      rgba(255,165,0,0.6),
      rgba(255,200,100,0.5),
      rgba(255,230,200,0.4),
      white 70%);
  filter: blur(100px);
  animation: spreadSmoke 8s infinite ease-out;
  z-index: 1;
}

@keyframes spreadSmoke {
  0% { transform: scale(0.1); opacity: 0.8; }
  70% { opacity: 0.6; }
  100% { transform: scale(1); opacity: 0; }
}
/* END */


/* TABLE WATERMARK LOGO */
.table-watermark {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none; /* allows clicking through */
  z-index: 0;           /* behind table content */
  opacity: 0.35;         /* adjust transparency */
}

.table-watermark img {
  width: 350px;          /* adjust size */
  height: auto;
  object-fit: contain;
  user-select: none;
}
/* END */



/* HIGHLIGHT TABLE */
  /* Highlight entire row on hover */
  #evaluationTable tbody tr:hover {
    background-color: rgba(147, 197, 253, 0.2);
  }

  /* PO Score cell styling */
  .po-score {
    font-weight: bold;
    text-align: center;
    border-radius: 4px;
    padding: 2px 6px;
  }

  .po-score.low {
    background-color: #fee2e2;
    color: #b91c1c;
  }

  .po-score.ok {
    background-color: #d1fae5;
    color: #065f46;
  }
  /* END */
    </style>
