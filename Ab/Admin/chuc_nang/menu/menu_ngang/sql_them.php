<?php
	#echo $_POST['txtMenu_ngang'];
	
	if($_POST['txtMenu_ngang'] != "")
	{	
		#echo $_POST['txtMenu_ngang'];
		#echo $_POST['txtLien_ket']	;
		#echo $_POST['cap_do']	;
		mysql_query("INSERT  INTO menu(id,ten,vitri_menu,lien_ket,thuoc_menu) VALUES ('NULL','$_POST[txtMenu_ngang]','ngang' ,'$_POST[txtLien_ket]','$_POST[cap_do]') ");
		#mysql_query($chuoi1);
		
	}else{
		thongbao("Kh�ng du?c b? tr?ng  t�n menu m?i.");
	}
?>