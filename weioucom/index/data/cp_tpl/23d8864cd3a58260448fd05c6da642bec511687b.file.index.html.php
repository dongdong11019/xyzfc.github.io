<?php /* Smarty version Smarty-3.1.19, created on 2017-01-13 17:28:07
         compiled from "/var/www/dev/web/index/tpl/index/index.html" */ ?>
<?php /*%%SmartyHeaderCode:86728320358749965ed4464-18853779%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '23d8864cd3a58260448fd05c6da642bec511687b' => 
    array (
      0 => '/var/www/dev/web/index/tpl/index/index.html',
      1 => 1484299629,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86728320358749965ed4464-18853779',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58749966082304_02539771',
  'variables' => 
  array (
    'iosDownloadUrl' => 0,
    'androidDownloadUrl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58749966082304_02539771')) {function content_58749966082304_02539771($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        
        
        <title>为偶 - 看世界</title>
        
        
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <!--header start-->
        <?php echo $_smarty_tpl->getSubTemplate ('header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--header end-->
        <!--main start-->
        <div class="home-page">
            <div class="banner">
                <div class="wrap">
                    <h1>为偶 看世界</h1>
                    <p>发现缤纷世界、分享美丽感受</p>
                    <a class="btn-go" href="/user">Get Started</a>
                    <div class="app-download">
                        <div class="fl">
                            <a class="down_ios" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['iosDownloadUrl']->value;?>
"><img height="44" src="/images/app-store.png" alt=""/></a>
                            <a class="down_android" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['androidDownloadUrl']->value;?>
"><img height="52" src="/images/google-store.png" alt=""/></a>
                        </div>
                        <div class="fl ewm-img"><img height="104" src="/images/ewm-img.png" alt=""/></div>
                    </div>
                </div>
            </div>

            <div class="intro">
                <ul>
                    <li>
                        <div class="wrap about">
                            <h2>weiou  应用程序</h2>
                            <p>拍照 、分享、 交流、 体验</p>
                            <div class="about-info">
                                <img class="about-img" src="/images/about-img.png" alt=""/>
                                <p class="about-text">
                                    世界那么大，你想怎么看？<br/>
                                    缤纷世界随指尖呈现，美好心情因像素飞扬。
                                </p>
                            </div>
                        </div>
                    </li>

<!--                    <li>
                        <div class="wrap activity">
                            <h2>WeiOu  主题活动</h2>
                            <p>为偶·看世界2016摄影交流比赛</p>
                            <div class="activity-info">
                                <img height="276" src="/img/ActivitiesBg.png" />
                                <img class="activity-img" src="/img/ActivitiesImg.png" alt=""/>
                                <p class="activity-text">
                                    行走家乡，一条小路\一道小吃\一个微笑\一抔黄土...<br/>
                                    总能触碰深藏的记忆，记录此刻，分享最美故乡
                                </p>
                            </div>
                        </div>
                    </li>-->
                </ul>
            </div>
            <div style="height: 88px;">
            </div>
            <div class="photo-tab">
                <?php echo $_smarty_tpl->getSubTemplate ('discover/photo-tab.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            <div class="photo-list">
                <div class="wrap" id="photo-list" style="min-height: 600px">

                </div>
            </div>
            <div class="index-btn-group">
                <div class="wrap">
                    <a href="/signup" class="btn btn-register mr30">注 册</a>
                    <a href="/discover" class="btn btn-more">更多精彩</a>
                </div>
            </div>
        </div>
        <!--main end-->
        <!--footer start-->
        <div class="footer">
            <div class="wrap">
                <div class="footer-l">
                    <h3>关于</h3>
                    <p>为偶是一个发现缤纷世界、分享深度感悟的图片社区交流平台。我们希望这里能激发你的热情。光，总是能让平凡变得神奇，不同的角度，不同的精彩，宝藏就一直在你心中。</p>
                </div>
                <div class="footer-m">
                    <div class="column">
                        <h3>为偶</h3>
                        <ul>
                            <li><a href="/about/about_us">关于为偶</a></li>
                            <li><a href="/about/join_us">加入我们</a></li>
                            <li><a href="/about/contacts">联系我们</a></li>
                            <li><a href="/about/terms">用户协议</a></li>
                            <li><a href="/about/privacy">隐私政策</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <h3>社交媒体</h3>
                        <ul>
                            <li><a href="" title="WeiouApp">微 信</a></li>
                            <li><a target="_blank" href="http://weibo.com/WeiouApp" title="weibo.com/WeiouApp">微 博</a></li>
                            <li><a target="_blank" href="http://facebook.com/WeiouApp" title="facebook.com/WeiouApp">Facebook</a></li>
                            <li><a target="_blank" href="http://twitter.com/WeiouApp" title="twitter.com/WeiouApp">Twitter</a></li>
                            <li><a target="_blank" href="http://pinterest.com/WeiouApp" title="pinterest.com/WeiouApp">Pinterest</a></li>
                            <li><a target="_blank" href="http://instagram.com/WeiouApp" title="instagram.com/WeiouApp">Instagram</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-r">
                    <h3>下载</h3>
                    <div class="ewm-img"><img height="104" src="/images/ewm-img.png" alt=""/></div>
                    <div class="fl">
                        <a class="down_ios" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['iosDownloadUrl']->value;?>
"><img height="44" src="/images/app-store.png" alt=""/></a>
                        <a class="down_android" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['androidDownloadUrl']->value;?>
"><img height="52" src="/images/google-store.png" alt=""/></a>
                    </div>
                </div>
            </div>
            <?php echo $_smarty_tpl->getSubTemplate ('footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
        <!--footer end-->
        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <script type="text/javascript" src="/js/index.js"></script>
        <script type="text/javascript" src="/js/scrollDataFormat.js"></script>
        <link type="text/css" rel="stylesheet" href="/css/unslider.css" />
        <link type="text/css" rel="stylesheet" href="/css/unslider-dots.css" />
        <script type="text/javascript" src="/js/unslider-min.js"></script> <!-- but with the right path! -->

<!--        <script type = "text/javascript">
            $(function () {
                $('.intro').unslider({
                    speed: 1,
                    delay: 8000,
                    arrows: false,
                    autoplay: true,
                    infinite: true
                });
            });
        </script>-->

    </body>
</html><?php }} ?>