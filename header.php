<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html" charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="KanjiClmb is a Japanese kanji learning web app that uses SRS to learn in the order recommended by J.W.Heisig.">
    <?php wp_head(); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Roboto|Work+Sans:400,600" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  </head>
  <body <?php body_class(); ?>>
    <header class="site-header">
      <div class="site-header__logo">Kanji Climb</div>
      <?php if(is_user_logged_in()){?>
      <div class="site-header__menu-icon">
        <div class="site-header__menu-icon__middle"></div>
      </div>
        <?php } ?>
      <div class="site-header__access-buttons">
        <?php if(!is_user_logged_in()){?>
          <a href="<?php echo wp_registration_url();  ?>" class="btn btn--primary">Sign Up</a>
          <a href="<?php echo wp_login_url(); ?>" class="btn">Login</a>
        <?php }else{?>

      <?php  } ?>
    </div>
  </header>

    <?php if(is_user_logged_in()){?>
  <nav class="primary-nav">
      <ul class="">

        <li><a class="<?php if (is_page('statistics')) echo ' is-current-link' ?>" href="<?php echo site_url('/statistics') ?>">My Dashboard</a></li>
        <li><a class="<?php if (is_page('account')) echo ' is-current-link' ?>" href="<?php echo site_url('/account') ?>">My Account</a></li>
        <li><a class="<?php if (is_page('subscription')) echo ' is-current-link' ?>" href="<?php echo site_url('/subscription') ?>">My Subscription</a></li>
        <li><a href="<?php echo wp_logout_url()?>" class="">Log Out</a></li>

      </ul>
  </nav>
<?php }?>
