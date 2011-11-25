<?
	session_start();
?>
<? include ("header.php") ?>
	<div id="inner">

		<div id="main">
			<div id="xbgA"></div>
	
			<div id="main_inner">

				<!-- Main start -->
	
				<h2><strong>Control</strong> de acceso</h2>
				<div class="content">
	
					<form name="autenticacion" action="/auth.php" method="POST">
					<img src="images/floripondio.png" class="cpic right" alt="" />
					<p><strong>Esta</strong> es una web privada. Por favor, aut&eacute;nticate con la identificaci&oacute;n que se te facilit&oacute; al inscribirte. Si no te has inscrito, habla con Enrique Puig, Alberto Fern&aacute;ndez, F&eacute;lix Velasco o Jorge Vadillo. Si no les conoces, me temo que te has equivocado de sitio.</p>
					<p>
<?
	if (isset($_SESSION['mensaje'])) {
		echo $_SESSION['mensaje'];
		unset($_SESSION['mensaje']);
	}
?>
					</p>
					<table>
						<tr>
							<td class="second"><strong>Usuario:</strong></td>
							<td class="second"><input type="text" name="username" /></td>
						</tr>
						<tr>
							<td class="second"><strong>Password:</strong></td>
							<td class="second"><input type="password" name="password" /></td>
						</tr>
						<tr>
							<td class="second"/>
							<td class="second"><input type="submit" class="btn" value="Enviar"></td>
						</tr>
					</table>
					</form>
				</div>
				<div class="foot"></div>
	
				<!-- Main End -->
				<div class="foot"></div>				
			</div>
	
		</div>
	
		<div id="side">
			<!-- Side start -->

			<h3><strong>&uacute;ltimos</strong> comentarios</h3>
			<div class="content">
				<p></p>
				<div class="divider"></div>
			</div>
			<!-- Side end -->
		</div>
		<div id="xbgB" class="foot"></div>
	</div>

</div>

<div id="footer">
	&copy; 2008 MyWebsiteName. Design by <a href="http://www.nodethirtythree.com">NodeThirtyThree</a> + <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>
</div>

</body>
</html>
