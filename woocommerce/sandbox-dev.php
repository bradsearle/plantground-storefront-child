<?php
/* Template Name: Dev Sandbox */
get_header(); 
?>
<!-- 
<style>
  .hero {
    height: auto;
    display: block;
    align-items: flex-start;
    justify-content: flex-start;
    margin-top: 100px;
  }
  .hero__mask {
    overflow: hidden;
    height: 5.5rem; /* tightly matches font size and line height */
  }
  .hero__title {
    font-size: 3.5rem;
    font-weight: 500;
    line-height: 1.1;
    display: flex;
    color: #111;
  }
  .hero__word {
    display: inline-block;
    transform: translateY(100%);
    will-change: transform;
    white-space: nowrap;
  }
  .hero__word--second {
    display: flex;
  }
</style>

<script>
window.addEventListener("DOMContentLoaded", () => {
  const tl = gsap.timeline();
  const customEase = "cubic-bezier(0.215, 0.61, 0.355, 1)";

  tl.to(".hero__word--first", {
    y: 0,
    opacity: 1,
    duration: 1.1,
    ease: customEase,
  }, 0);

  tl.to(".hero__word--second", {
    y: 0,
    opacity: 1,
    duration: 1.1,
    ease: "back.inOut(1)",
  }, 0.1);
});
</script> -->

<!-- Hero section (optional, commented out)
<section class="hero">
  <div class="hero__mask">
    <h1 class="hero__title">
      <span class="hero__word hero__word--first">Slinging unique cactus, succulents, and originals, mostly bare root,</span>
    </h1>
  </div>
</section>
-->

<style>
    .test {
        font-size: 48px;
        font-weight: bold;
        text-align: center;
        margin-top: 200px;
    }   

    .flex-row {
  display: flex;
  justify-content: space-between;
  align-items: center; /* Optional: vertically centers items if container has height */
  width: 100%;
  /* Add padding or max-width as needed for your layout */
}
</style>


<div class="test">Sandbox Dev Page</div>



<div class="flex-row">
  <div class="left-item">Left Content</div>
  <div class="right-item">Right Content</div>
</div>




<?php get_footer(); ?>

