<?php /* Smarty version Smarty-3.1.19, created on 2017-01-11 10:20:48
         compiled from "/var/www/dev/web/index/tpl/about/about_us.html" */ ?>
<?php /*%%SmartyHeaderCode:75236506358759680a10f17-21933933%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b50594e8e33de087f925216d024422e770622163' => 
    array (
      0 => '/var/www/dev/web/index/tpl/about/about_us.html',
      1 => 1484018912,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '75236506358759680a10f17-21933933',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58759680a64ea7_91692857',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58759680a64ea7_91692857')) {function content_58759680a64ea7_91692857($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>关于为偶</title>
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <!--header start-->
<!--        <script type = "text/javascript" src = "/js/header-about.js"></script>
        <script type = "text/javascript">
            document.write(getAboutHeader(1));
        </script>-->

        <?php echo $_smarty_tpl->getSubTemplate ('header-about.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--header end-->
        <!--main start-->
        <div class="inner-banner banner-about">
            <div class="wrap">
                <h1>发现世界</h1>
                <p>做有灵魂的照片交流社区，探索、分享世界各<br/>地不同风情的照片，展现你眼中的世界，让照<br/>片具有自己的价值</p>
            </div>
        </div>
        <div class="purpose">
            <div class="purpose-main">
                <h2>I am forever chasing light. Light turns the ordinary into the magical. </h2>
                <p>—Trent Parke</p>
            </div>
        </div>
        <div class="about-us">
            <div class="about-us-main">
                <h2>项目起源</h2>
                <p>几年来，我们（为偶读书，微信公众号 weiouorg）一直在努力为农村的孩子们送去课外书，引导他们阅读，帮助他们探索小脑瓜里各种各样的问题。有一次在江西，孩子们问外面的世界是什么样子的？志愿者的解答是拿出手机，给他们看布达拉宫、鱼尾狮、金门大桥，小鹿、松鼠、火鸡，玫瑰、红杉、无花果等。在孩子们“哇”“这是什么”“这是哪里”的叽叽喳喳中，志愿者思索：更好的答案是做一款应用，集大众之力，如果每个人把他认为值得给孩子看的人、景、物、事分享给他们，就有可能在他们有能力走出乡村之前多多少少看看这些“什么”、“哪里”，帮助他们“看世界”</p>
                <p>这款应用不解决疼点、刚需，它是心的呼唤，每个人的每一次参与、每一张照片都会在地图上增加一点。分享成了帮助，多了新的意义，只要你相信，我们一起努力，“看”世界的梦想就会成为现实。</p>
            </div>
        </div>
        <!--main end-->
        <!--footer start-->
        <?php echo $_smarty_tpl->getSubTemplate ('footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--footer end-->


        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </body>
</html>
<?php }} ?>