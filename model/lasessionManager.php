<?php



class lasessionManager
{



private $db;

public function __construct(MyPDO $connect)
{

	$this->db = $connect;
}

public function sessionSelectALL():array{

	$sql = "SELECT * FROM lasession ORDER BY lenom ASC;";
	$recup = $this->db->query($sql);
	if ($recup->rowCount() === 0) {
		return [];
	}
	return $recup->fetchAll(PDO::FETCH_ASSOC);
   }



public function sessionSelectByID(int $idlasession): array {

  if (empty($idlasession))
  
  {   
			  
	return[];

  }


$sql="SELECT * FROM lasession where idlasession=?;";
$recup = $this->db->prepare($sql);
$recup->bindValue(1, $idlasession, PDO::PARAM_INT);
$recup->execute();


if ($recup->rowCount() === 0) {
	return [];
}
return $recup->fetch(PDO::FETCH_ASSOC);
}


public function sessionCreate(lasession $lasession) {

if( empty($lasession->getLenom()) || empty($lasession->getLacronyme()) || empty($lasession->getLannee()) || empty($lasession->getLenumero())|| empty($lasession->getLetype()) || empty($lasession->getDebut()) || empty($lasession->getFin()) ) {
   return false;

}

$sql="INSERT INTO lasession (lenom, lacronyme, lannee, lenumero, letype, debut, fin, lafiliere_idfiliere) VALUE(?, ?, ?, ?, ?, ?, ?, ?);";

$insert= $this->db->prepare($sql);
$insert->bindValue(1,$lasession->getLenom(),PDO::PARAM_STR);
$insert->bindValue(2, $lasession->getLacronyme(), PDO::PARAM_STR);
$insert->bindValue(3, $lasession->getLannee(), PDO::PARAM_INT);
$insert->bindValue(4, $lasession->getLenumero(), PDO::PARAM_INT);
$insert->bindValue(5, $lasession->getLetype(), PDO::PARAM_INT);
$insert->bindValue(6, $lasession->getDebut(), PDO::PARAM_STR);
$insert->bindValue(7, $lasession->getFin(), PDO::PARAM_STR);
$insert->bindValue(8, $lasession->getLafiliere_idfiliere(), PDO::PARAM_INT);

try{

	$insert->execute();
	return true;

} catch(PDOException $a){
	
	echo '<h2 style="color: red;">ERROR: ' . $a->getMessage() . '</h2>';
	return false;     
	
}






} 




public function sessionUpdate(lasession $lasession){


	if ( empty($lasession->getLenom()) || empty($lasession->getLacronyme()) || empty($lasession->getLannee()) || empty($lasession->getLenumero()) || empty($lasession->getLetype()) || empty($lasession->getDebut()) || empty($lasession->getFin()) || empty($lasession->getIdlasession()) ) {
		return false;
}

	$sql ="UPDATE lasession SET lenom=?, lacronyme=?, lannee=?, lenumero=?, letype=?, debut=?, fin=?, lafiliere_idfiliere=?, actif=? WHERE idlasession=?";


	$update = $this->db->prepare($sql);
	$update->bindValue(1, $lasession->getLenom(), PDO::PARAM_STR);
	$update->bindValue(2, $lasession->getLacronyme(), PDO::PARAM_STR);
	$update->bindValue(3, $lasession->getLannee(), PDO::PARAM_INT);
	$update->bindValue(4, $lasession->getLenumero(), PDO::PARAM_INT);
	$update->bindValue(5, $lasession->getLetype(), PDO::PARAM_INT);
	$update->bindValue(6, $lasession->getDebut(), PDO::PARAM_STR);
	$update->bindValue(7, $lasession->getFin(), PDO::PARAM_STR);
	$update->bindValue(8, $lasession->getLafiliere_idfiliere(), PDO::PARAM_INT);
    $update->bindValue(9, $lasession->getActif(),PDO::PARAM_INT);
	$update->bindValue(10, $lasession->getIdlasession(),PDO::PARAM_INT);

	try{

		$update->execute();
		return true;

	} catch(PDOException $e){

		echo '<h2 style="color: red;">ERROR: ' . $e->getMessage() . '</h2>';
		return false;

	}

}


public function sessionDelete(int $idlasession){

   $sql="DELETE FROM lasession WHERE idlasession=?";

$delete =$this->db->prepare($sql);
$delete->bindValue(1,$idlasession,PDO:: PARAM_INT); 

try{
   $delete->execute();
	 return true;

}catch(PDOException $e){
	echo $e->getCode();

	 return false;

}


}

public function selectSessionCount(): int {

	$sql="SELECT COUNT(idlasession) AS nb
		  FROM lasession";
		  

	 $sqlQuery = $this->db->query($sql);


	 $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
	 return (int) $recup['nb'];


	 $recup= $sqlQuery->fetch(PDO::FETCH_ASSOC);	  
	 return (int) $recup['nb'];

}


public function selectSessionWithLimit(int $pageSession,int $nbParPageSession): array{


	$premsLIMIT = ($pageSession-1)*$nbParPageSession;
	$sql = "
	SELECT
		*
	FROM
		lasession
	ORDER BY debut
	LIMIT  ?, ?
	";
	$sqlQuery = $this->db->prepare($sql);
	$sqlQuery->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
	$sqlQuery->bindValue(2,$nbParPageSession,PDO::PARAM_INT);
	$sqlQuery->execute();
	
	return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);


}

// ***** Référent pédagogique méthodes ***** //

public function sessionDeletePedagogique(int $idlasession){

$sql ="UPDATE lasession SET actif=0 WHERE idlasession=?";


	$update = $this->db->prepare($sql);
	
	$update->bindValue(1, $idlasession,PDO::PARAM_INT);

	try{

		$update->execute();
		return true;

	} catch(PDOException $e){

		echo '<h2 style="color: red;">ERROR: ' . $e->getMessage() . '</h2>';
		return false;

	}

}

public function selectSessionCountPedagogique(): int {

	$sql="SELECT COUNT(idlasession) AS nb
		  FROM lasession
		  WHERE actif=1";
		  

	 $sqlQuery = $this->db->query($sql);


	 $recup = $sqlQuery->fetch(PDO::FETCH_ASSOC);
	 return (int) $recup['nb'];


	 $recup= $sqlQuery->fetch(PDO::FETCH_ASSOC);	  
	 return (int) $recup['nb'];

}

public function selectSessionWithLimitPedagogique(int $pageSession,int $nbParPageSession): array{


	$premsLIMIT = ($pageSession-1)*$nbParPageSession;
	$sql = "
	SELECT
		*
	FROM
		lasession
	WHERE actif=1
	ORDER BY debut
	LIMIT  ?, ?
	";
	$sqlQuery = $this->db->prepare($sql);
	$sqlQuery->bindValue(1,$premsLIMIT,PDO::PARAM_INT);
	$sqlQuery->bindValue(2,$nbParPageSession,PDO::PARAM_INT);
	$sqlQuery->execute();
	
	return $sqlQuery->fetchAll(PDO::FETCH_ASSOC);


}

}