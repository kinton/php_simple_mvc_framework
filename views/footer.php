</div>
<div id="footer" class="site-footer">
	<div class="footer-content">
		<div class="footer-menu-handler">
			<div class="footer-menu-column">
				<header>Продукция</header>
				<div class="footer-column-menu">
					<a href="/catalog"><div class="menu-element">Меню</div></a>
					<a href="/pages/delivery"><div class="menu-element">Доставка</div></a>
					<a href="/news"><div class="menu-element">Акции и новости</div></a>
				</div>
			</div>
			<div class="footer-menu-column">
				<header>О компании</header>
				<div class="footer-column-menu">
					<a href="/pages/about"><div class="menu-element">О нас</div></a>
					<a href="/pages/contacts"><div class="menu-element">Контакты</div></a>
					<a href="tel:<?php echo explode("\n", $this->siteConstants['phones'])[0];?>"><div class="menu-element"><?php echo explode("\n", $this->siteConstants['phones'])[0];?></div></a>
				</div>
			</div>
			<div class="footer-menu-column footer-social-handler">
				<header>Соц. сети</header>
				<div class="footer-column-menu">
					<?php $social = explode("\n", $this->siteConstants['socialNetworkLinks']);
						for ($i=0; $i < count($social); $i++) {
							$locSocial = explode(" ", $social[$i]);
							switch ($locSocial[1]) {
								case 'vk':
									$imgSrc = '/public/images/social_networks/vk-circle.png';
									break;								
								default:
									$imgSrc = '/public/images/social_networks/vk-circle.png';
									break;
							}
							echo '<a href="',$locSocial[0],'"><img src="',$imgSrc,'" alt="" class="social-network-icon"></a>';
						}
					?>
				</div>
			</div>
			<div class="footer-menu-column footer-social-handler" style="background: white;">
				<img style="width: 100%; background: white;" src="/public/images/Momento2TR.png" alt="<?=$this->siteConstants['siteName']?>">
			</div>
		</div>
		<hr>
		<div class="footer-copy-policy">
			<?php if (date('Y') == 2018): ?>
	  			<div class="footer-col"><h1>&copy; <?=$this->siteConstants['siteName']?> 2018</h1></div>
	  		<?php else: ?>
	  			<div class="footer-col"><h1>&copy; <?=$this->siteConstants['siteName']?> 2018 — <?=date('Y')?></h1></div>
	  		<?php endif; ?>
	  		<div class="footer-col"><h1>Пицца Оренбург</h1></div>
	  		<!--<div class="footer-col"><a href="https://vk.com/kintonk" style="color: black; text-decoration: none;">Developing — Constantine Kovalenko</a></div>-->
	  	</div>
  	</div>
</div>

<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter49676566 = new Ya.Metrika2({ id:49676566, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/tag.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks2"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/49676566" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

</body>
<script>
</script>
</html>