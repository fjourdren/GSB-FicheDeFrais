			<!-- FIN CONTENT -->
			</div>
			
			<!-- FOOTER -->
			<div class="footer">
				<p>GALAXY SWISS BOURDIN</p>
			</div>

		<!-- FIN WRAP -->
		</div>

	</body>
</html>

<?php
	//fermeture de la connexion à la base de donnée
	require_once 'include/AccesDonnees.lib.php';
	
	if(isset($connexion)) {
		deconnexion();
	}
?>