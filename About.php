<!DOCTYPE html>
<html>
<head>

  <meta charset="UTF-8">
  <title>NoSettles | Making A Better World</title>
  <meta name="description" content="Helping People Explore Their Abilities.">
	<?php include_once("head.php"); ?>

  <script type="text/javascript">
    $(function() {

      function imageParallax($img) {
        wHeight = $(window).height();
        wWidth = $(window).width();

        $img.height(wHeight);
        $img.width(wWidth + 16);
      };

      parallaxImg = $('.full-image-parallax');
      imageParallax(parallaxImg);
      $(window).resize(function() {
        imageParallax(parallaxImg);
      });

    });
  </script>

</head>
<body data-pageName="about">
<?php include_once("template_PageTop.php"); ?>

<main>
  <div class="parallax flex-box-culumn" >
    <h1 class="center-text">Learn More About NoSettles</h1>
    <hr>


    <div class="full-image-parallax img-parallax5">
      <h2 class="font-white med-margin-top med-padding huge-font">Learn Everyday</h2>
    </div>
    <div class="full-stretch">
      <div class="pageMiddle large-padding">
        <h1>What We Believe</h1>
        <p>We believe that you should never limit yourself. We believe that each individual has the ability to learn or achieve any set of skills they desire. We believe that no individual should limit itself to what he/she knows right now, but to think of what one can achieve and do in the long run. We have made this site in order to help you become your true self, to find yourself, experiment with your abilities and to see what you can achieve.</p>
      </div>
    </div>

    <div class="full-image-parallax img-parallax2">
      <h2 class="font-white med-margin-top med-padding huge-font">Let’s Not Settle</h2>
    </div>
    <div class="full-stretch">
      <div class="pageMiddle large-padding">
        <h1>Our Vision</h1>
        <p>Our vision is clear. To help people to find themselves in their abilities, hobbies and interests. We want this website to stop people from settling with what they know or have now and to push themselves into their greater selves. We want to enlighten people, to inspire and to help people. Our vision is for everyone to <strong><em>Learn Something New.</em></strong></p>
      </div>
    </div>

    <div class="full-image-parallax img-parallax6">
      <h2 class="font-white med-margin-top med-padding huge-font"><em>Learn Something New</em></h2>
    </div>
    <div class="full-stretch">
      <div class="pageMiddle large-padding">
        <h1>Our Moto</h1>
        <p>Our moto is clear, simple and self-explanatory. Its use is to help people and to push them into learning something new. It is to help them to step out of their comfort zone and to explore their abilities. It's to encourage them to find their inner selves and amaze themselves of what they can achieve. Most of us have talents that we have not discovered yet, and some never do, and we're here to change that.</p>
      </div>
    </div>

    <div class="full-image-parallax img-parallax7">
      <h2 class="font-white med-margin-top med-padding huge-font">Let’s Start Today</h2>
    </div>
    <div class="full-stretch">
      <div class="pageMiddle large-padding">
        <h1>The Team</h1>
        <img src="style/ceo.png" class="center circle" alt="Ceo and Founder of NoSettles" />
        <h3 class="margin-none small-margin-top center-text font-gray">Ceo &amp; Founder of NoSettles</h3>
        <h2 class="margin-none center-text">Arvin Ansari</h2>
        <p class="center-text">Yep, that's right. Currently NoSettles is run only by a <em>15 year-old teenager</em> and that is of course me, the one that's written every single code for the site. I'm currently working all by myself, but I'm hoping of building an amazing team that the users of this site deserve. If you are interested in joining the NoSettles team, please <a href="contact">Contact Us</a>!</p>
      </div>
    </div>


  </div>

</main>


<?php include_once("template_PageBottom.php"); ?>
</body>
</html>
